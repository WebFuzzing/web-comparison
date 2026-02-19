package parsers.model.files;

import org.jgrapht.Graph;
import org.jgrapht.graph.ClassBasedEdgeFactory;
import org.jgrapht.graph.DirectedPseudograph;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;
import parsers.model.files.graph.elements.*;
import utils.DefaultValues;
import utils.GraphBuilder;
import utils.JGraphUtil;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathConstants;
import javax.xml.xpath.XPathExpressionException;
import javax.xml.xpath.XPathFactory;
import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.LinkedList;
import java.util.List;

/**
 * Do the parse of the SCXML Model
 *
 * @author raphaelrodrigues
 */
public class ParserSCXML implements IParserStrategy {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    private String modelName;
    private String initialNode;
    private String finalNode;

    @Override
    public String getModelName() {
        return modelName;
    }

    @Override
    public String getInitialNode() {
        return initialNode;
    }

    @Override
    public String getFinalNode() {
        return finalNode;
    }

    /**
     * Method that allows the parsing of each "super" state
     *
     * @param graph
     * @param g
     * @param element
     * @param doc
     */
    public void parseState(Graph graph, GraphBuilder g, Element element, Document doc) throws XPathExpressionException {
        // Add new node to the graph
        String actualState = element.getAttribute("id");
        Vertex v = ParserUtils.addVertexToGraph(graph, g, actualState);

        // Parse validations
        parseAsserts(element, v);

        // Parse Forms
        parseForm(graph, g, element, v, doc);

        // Parse Transitions
        parseTransitions(graph, g, element, v, actualState);
    }

    /**
     * Get tags Asserts/Onentry
     *
     * @param element
     * @param v
     */
    public void parseAsserts(Element element, Vertex v) {
        // Get all asserts tags in the first level of the Element
        List<Element> asserts = ParserUtils.getChildrenByTagName(element, "onentry");
        Validations validations = new Validations();

        for (Element eAssert : asserts) {
            validations.addValidation(eAssert.getAttribute("type"), eAssert.getAttribute("id"));
        }

        // Add the validation to the Vertex
        v.setValidations(validations);
    }

    /**
     * Parse the Forms
     *
     * @param graph
     * @param g
     * @param element
     * @param v
     * @param doc
     * @throws javax.xml.xpath.XPathExpressionException
     */
    public void parseForm(Graph graph, GraphBuilder g, Element element, Vertex v, Document doc) throws XPathExpressionException {
        List<Element> forms = ParserUtils.getChildrenByTagName(element, "state");

        // Every state in STATE node
        String idForm;

        for (Element formNode : forms) {

            Element formElem = formNode;

            idForm = formElem.getAttribute("id");

            // Initialize the Form
            ArrayList<Action> inputsForm = new ArrayList<>();
            Action action;

            // Get the inputs of the form
            NodeList inputs = formElem.getElementsByTagName("send");
            for (int z = 0; z < inputs.getLength(); z++) {
                // For each element insert a step
                formElem = (Element) inputs.item(z);
                action = new Action(formElem.getAttribute("label"), formElem.getAttribute("type"), formElem.getAttribute("element"));
                inputsForm.add(action);
            }

            // Check for onexit tags
            NodeList onexit = formNode.getElementsByTagName("onexit");
            List<Validation> formValidations = getValidationsOnForm(onexit);

            // Get Transitions FORMS
            NodeList transitions = formNode.getElementsByTagName("transition");
            for (int z = 0; z < transitions.getLength(); z++) {
                // For each element insert a step
                formElem = (Element) transitions.item(z);

                NodeList submits = formElem.getElementsByTagName("submit");

                Element submit = (Element) submits.item(0);

                String submitTarget = submit.getAttribute("target");

                Vertex aux = ParserUtils.addVertexToGraph(graph, g, submitTarget);

                /**
                 * ErrorTarget if it has error tags
                 */
                ErrorVertex errorVertex;
                NodeList errors = formElem.getElementsByTagName("error");
                Element error = (Element) errors.item(0);
                if (error != null) {
                    String errorTarget = error.getAttribute("target");
                    errorVertex = new ErrorVertex(errorTarget);

                    // Get through Xpath the error target
                    // Get the validation in the state
                    XPath xPath = XPathFactory.newInstance().newXPath();
                    String expression = "//state[@id='" + errorTarget + "']";
                    Node node = (Node) xPath.compile(expression).evaluate(doc, XPathConstants.NODE);

                    // Get the conditions from the error state
                    errorVertex = errorsFormsAsserts(node, errorVertex);

                    // Add the transition form in graph with the errors
                    Form form = new Form(idForm, formElem.getAttribute("id"), inputsForm, formElem.getAttribute("label"), formElem.getAttribute("type"), formElem.getAttribute("cond"), errorVertex, formValidations);
                    g.addEdge(v, aux, form);
                    graph.addEdge(v, aux, new Edge("Form" + formElem.getAttribute("id"), form));
                } else {
                    Form form = new Form(idForm, formElem.getAttribute("id"), inputsForm, formElem.getAttribute("label"), formElem.getAttribute("type"), formElem.getAttribute("cond"), formValidations);
                    g.addEdge(v, aux, form);
                    graph.addEdge(v, aux, new Edge("Form" + formElem.getAttribute("id"), form));
                }

            }

        }
    }

    /**
     * Get the validations to do after submit a form
     *
     * @param onexit
     * @return
     */
    private List<Validation> getValidationsOnForm(NodeList onexit) {
        List<Validation> formValidations = new LinkedList<>();

        for (int z = 0; z < onexit.getLength(); z++) {
            Element onexitElem = (Element) onexit.item(z);
            Validation auxValidation = new Validation(onexitElem.getAttribute("type"), onexitElem.getAttribute("id"));
            formValidations.add(auxValidation);
        }

        return formValidations;
    }

    /**
     * Get asserts(validations) from error states
     *
     * @param node
     * @param error
     * @return
     */
    private ErrorVertex errorsFormsAsserts(Node node, ErrorVertex error) {
        if (node != null) {
            NodeList nodeList = node.getChildNodes();
            for (int i = 0; nodeList != null && i < nodeList.getLength(); i++) {
                Node nod = nodeList.item(i);
                if (nod.getNodeType() == Node.ELEMENT_NODE) {
                    // Get for validation tags
                    Element assertElem = (Element) nodeList.item(i);
                    error.addValidation(assertElem.getAttribute("type"), assertElem.getAttribute("id"));
                }
            }
        }
        return error;
    }

    /**
     * Get the Transitions of the State
     *
     * @param graph
     * @param g
     * @param element
     * @param v
     * @param actualState
     */
    public void parseTransitions(Graph graph, GraphBuilder g, Element element, Vertex v, String actualState) {

        // Get the Normal Transitions
        NodeList transitions = element.getElementsByTagName("transition");
        List<Interaction> stepsMenu;

        // Go throw each <transitions> tag in the state
        for (int j = 0; j < transitions.getLength(); j++) {
            Element titleElem = (Element) transitions.item(j);

            // Verifies if this transition is inside a form
            if (!titleElem.hasAttribute("type")) {
                Vertex aux2 = ParserUtils.addVertexToGraph(graph, g, titleElem.getAttribute("target"));
                parseAsserts(titleElem, aux2);
                Call link = new Call(titleElem.getAttribute("target"), titleElem.getAttribute("id"), "click");
                g.addEdge(v, aux2, link);
                graph.addEdge(v, aux2, new Edge("Transition:" + titleElem.getAttribute("id"), link));
            } else
                // If the transition is a menu
                if ("menu".equals(titleElem.getAttribute("type"))) {
                    Element stepElem;
                    stepsMenu = new ArrayList<>();
                    int i;
                    NodeList steps = titleElem.getElementsByTagName("step");

                    // Each step is a call
                    for (i = 0; i < steps.getLength() - 1; i++) {
                        stepElem = (Element) steps.item(i);
                        Interaction interaction = new Interaction(stepElem.getAttribute("id"), "click");
                        stepsMenu.add(interaction);
                    }

                    // Get the last call, it will be transform in a Vertex
                    stepElem = (Element) steps.item(i);
                    Vertex aux2 = ParserUtils.addVertexToGraph(graph, g, titleElem.getAttribute("target"));

                    Call link = new Call(stepElem.getAttribute("target"), stepElem.getAttribute("id"), "click", stepsMenu);
                    g.addEdge(v, aux2, link);
                    graph.addEdge(v, aux2, new Edge("LINK" + stepElem.getAttribute("id"), link));

                }

        }
    }

    /**
     * Do the parse and transform on graph
     *
     * @param path path of the MODEL file
     * @return the Graph construct from the MODEL
     * @throws IOException
     */
    // Probably exists a better way of parse the file
    @Override
    public Graph parsing(String path) {
        try {
            // Construct document builder
            File stocks = new File(path);
            DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
            DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
            Document doc = dBuilder.parse(stocks);
            doc.getDocumentElement().normalize();

            modelName = doc.getDocumentElement().getAttribute("name");
            initialNode = doc.getDocumentElement().getAttribute("initial");
            finalNode = doc.getDocumentElement().getAttribute("final");

            if (initialNode == "") {
                logger.error("First node not defined!");
                return null;
            }

            // Get all the states of the SCXML file
            NodeList states = doc.getElementsByTagName("state");

            // Declare the graph
            DirectedPseudograph<Vertex, Edge> graph
                    = new DirectedPseudograph<>(new ClassBasedEdgeFactory<Vertex, Edge>(Edge.class));

            Graph cloneGraph = JGraphUtil.clone(graph);

            GraphBuilder<Vertex, Edge> g = new GraphBuilder<>(cloneGraph);

            Element element;

            // Traverse all the states in the file
            for (int i = 0; i < states.getLength(); i++) {
                element = (Element) states.item(i);
                // Parse the components of the state
                this.parseState(graph, g, element, doc);
            }
            return graph;
        } catch (IOException | ParserConfigurationException | XPathExpressionException | SAXException ex) {
            logger.error("Error parsing the file! " + ex.getMessage());
            return null;
        }
    }

}
