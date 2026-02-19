package parsers.model.files;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import org.apache.commons.io.IOUtils;
import org.jgrapht.Graph;
import org.jgrapht.graph.ClassBasedEdgeFactory;
import org.jgrapht.graph.DirectedPseudograph;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import parsers.model.files.graph.elements.Call;
import parsers.model.files.graph.elements.Edge;
import parsers.model.files.graph.elements.Vertex;
import utils.DefaultValues;
import utils.GraphBuilder;
import utils.JGraphUtil;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.util.Iterator;

/**
 * Created by Marcelo Gonçalves
 */
public class ParserEMDL implements IParserStrategy {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    private static final String TARGET = "target";
    private static final String NAME = "name";
    private static final String ID = "id";
    private static final String SOURCE = "source";

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

    @Override
    public Graph parsing(String path) {

        // Declare the graph0
        DirectedPseudograph<Vertex, Edge> graph = new DirectedPseudograph<>(new ClassBasedEdgeFactory<Vertex, Edge>(Edge.class));

        Graph cloneGraph = JGraphUtil.clone(graph);

        GraphBuilder<Vertex, Edge> g = new GraphBuilder<>(cloneGraph);

        InputStream is = null;
        String jsonTxt = null;
        try {
            is = new FileInputStream(path);
            jsonTxt = IOUtils.toString(is);
        } catch (IOException e) {
            logger.error("Error generating the graph! " + e);
        }

        JsonObject jObject = new JsonParser().parse(jsonTxt).getAsJsonObject();
        JsonObject desc = jObject.getAsJsonObject("descriptor");
        JsonObject charts = jObject.getAsJsonObject("chart");

        if (desc == null || charts == null || !desc.has("chart_name")) {
            // Json Malformed file
            logger.error("Json Malformed file - Missing some of these components (descriptor, chart or chart_name)!");
            return null;
        }

        this.modelName = desc.get("chart_name").getAsString();

        JsonArray states = charts.getAsJsonArray("states");
        JsonArray transitions = charts.getAsJsonArray("transitions");
        JsonArray iTransitions = charts.getAsJsonArray("initial_transitions");

        if (states == null || transitions == null || iTransitions == null) {
            // Json Malformed file
            logger.error("Json Malformed file - Missing some of these components (states, transitions or initial_transitions)!");
            return null;
        }

        try {
            this.readStates(graph, g, states);
            this.readTransitions(graph, g, transitions);
            this.readInitialTransitions(graph, g, iTransitions);
        } catch (IOException e) {
            logger.error("Error generating the graph! " + e);
        }

        return graph;
    }

    private void readStates(Graph graph, GraphBuilder g, JsonArray states) throws IOException {

        Iterator it = states.iterator();

        while (it.hasNext()) {
            JsonElement st = (JsonElement) it.next();
            JsonObject state = st.getAsJsonObject();
            if (state.has(NAME)) {
                ParserUtils.addVertexToGraph(graph, g, state.get(NAME).getAsString());
            } else {
                logger.error("The state below has no name!");
                logger.error(state.toString());
            }
        }
    }

    private void readTransitions(Graph graph, GraphBuilder g, JsonArray transitions) throws IOException {

        Iterator it = transitions.iterator();

        while (it.hasNext()) {
            JsonElement st = (JsonElement) it.next();
            JsonObject transition = st.getAsJsonObject();
            if (transition.has(NAME)
                    && transition.has(ID)
                    && transition.has(SOURCE)
                    && transition.has(TARGET)) {

                String tId = transition.get(ID).getAsString();
                String tName = transition.get(NAME).getAsString();

                JsonObject sObject = transition.getAsJsonObject(SOURCE);
                JsonObject tObject = transition.getAsJsonObject(TARGET);

                if (!sObject.has(NAME) || !tObject.has(NAME)) {
                    logger.error("The initial transition below has no source/target name!");
                    logger.error(transition.toString());
                }

                String source = sObject.get(NAME).getAsString();
                String target = tObject.get(NAME).getAsString();

                Vertex vTarget = JGraphUtil.find(graph, target);
                Vertex vSource = JGraphUtil.find(graph, source);

                Call link = new Call(tId, tName, "click");
                g.addEdge(vSource, vTarget, link);
                graph.addEdge(vSource, vTarget, new Edge("LINK" + tName, link));

            } else {
                logger.error("The transition below has some error!");
                logger.error(transition.toString());
            }
        }
    }

    private void readInitialTransitions(Graph graph, GraphBuilder g, JsonArray initialTransitions) throws IOException {

        Iterator it = initialTransitions.iterator();

        while (it.hasNext()) {
            JsonElement st = (JsonElement) it.next();
            JsonObject iTransition = st.getAsJsonObject();
            if (iTransition.has(NAME) && iTransition.has(ID) && iTransition.has(TARGET)) {
                String tId = iTransition.get(ID).getAsString();
                String tName = iTransition.get(NAME).getAsString();

                JsonObject tObject = iTransition.getAsJsonObject(TARGET);

                if (!tObject.has(NAME)) {
                    logger.error("The initial transition below has no target name!");
                    logger.error(iTransition.getAsString());
                }

                String target = tObject.get(NAME).getAsString();

                // First Vertex
                Vertex vSource = ParserUtils.addVertexToGraph(graph, g, tName);
                initialNode = tName;

                // Add Edge
                Vertex vTarget = JGraphUtil.find(graph, target);

                Call link = new Call(tId, tName, "click");
                g.addEdge(vSource, vTarget, link);
                graph.addEdge(vSource, vTarget, new Edge("LINK" + tName, link));

            } else {
                logger.error("The transition below has some error!");
                logger.error(iTransition.toString());
            }
        }
    }
}