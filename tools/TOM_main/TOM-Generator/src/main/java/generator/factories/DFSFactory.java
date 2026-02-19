package generator.factories;

import generator.algorithms.AllPathsBFS;
import generator.algorithms.AllPathsDFS;
import org.jgrapht.Graph;
import parsers.model.files.graph.elements.Edge;
import parsers.model.files.graph.elements.Vertex;

/**
 * Created by Marcelo Gonçalves
 */
public class DFSFactory extends AlgorithmsAbstractFactory {

    @Override
    public AllPathsDFS getAllPathsDFS(Graph<Vertex, Edge> graph, String startNode, String finalNode, int maxVertexVisit, int maxEdgeVisit) {
        return new AllPathsDFS(graph, startNode, finalNode, maxVertexVisit, maxEdgeVisit);
    }

    @Override
    AllPathsBFS getAllPathsBFS(Graph<Vertex, Edge> graph, String startNode, String finalNode, int maxVertexVisit, int maxEdgeVisit) {
        return null;
    }
}
