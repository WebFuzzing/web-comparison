package parsers.model.files;

import org.jgrapht.Graph;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import parsers.model.files.graph.elements.Vertex;
import utils.GraphBuilder;
import utils.JGraphUtil;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by mgonc on 03/03/17.
 */
public class ParserUtils {

    private ParserUtils() {
        throw new IllegalAccessError("Utility class");
    }

    /**
     * Check if the Vertex exists in the Graph
     * Add a new vertex if it doesn't exist
     *
     * @param graph
     * @param vertexName
     * @return The vertex
     */
    public static Vertex addVertexToGraph(Graph graph, GraphBuilder g, String vertexName) {
        Vertex aux = JGraphUtil.find(graph, vertexName);

        if (aux == null) {
            aux = new Vertex(vertexName);
            g.addVertex(aux);
            graph.addVertex(aux);
        }

        return aux;
    }

    /**
     * Get the children tags (e.g in first level)
     * If we don't do this he can get all tags in other levels
     * In this case we only want the first level
     *
     * @param parent
     * @param name
     * @return
     */
    public static List<Element> getChildrenByTagName(Element parent, String name) {
        List<Element> nodeList = new ArrayList<>();
        for (Node child = parent.getFirstChild(); child != null; child = child.getNextSibling()) {
            if (child.getNodeType() == Node.ELEMENT_NODE && name.equals(child.getNodeName())) {
                nodeList.add((Element) child);
            }
        }

        return nodeList;
    }

}
