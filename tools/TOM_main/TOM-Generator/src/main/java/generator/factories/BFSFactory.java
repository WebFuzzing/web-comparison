package generator.factories;

import generator.algorithms.AllPathsBFS;
import generator.algorithms.AllPathsDFS;
import generator.algorithms.ITraversalStrategy;
import org.jgrapht.Graph;
import parsers.model.files.graph.elements.Edge;
import parsers.model.files.graph.elements.Vertex;

/**
 * Created by Marcelo Gonçalves
 */
public class BFSFactory extends AlgorithmsAbstractFactory {

    @Override
    AllPathsDFS getAllPathsDFS(Graph<Vertex, Edge> graph, String startNode, String finalNode, int maxVertexVisit, int maxEdgeVisit) {
        return null;
    }

    @Override
    public AllPathsBFS getAllPathsBFS(Graph<Vertex, Edge> graph, String startNode, String finalNode, int maxVertexVisit, int maxEdgeVisit) {
        return new AllPathsBFS(graph, startNode, finalNode, maxVertexVisit, maxEdgeVisit);
    }
}
