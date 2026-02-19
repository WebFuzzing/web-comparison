package utils;

import org.jgrapht.Graph;
import parsers.model.files.graph.elements.Edge;
import parsers.model.files.graph.elements.Event;

/**
 * Helper to create a Graph
 *
 * @param <V>
 * @param <E>
 * @author raphaelrodrigues
 */
public class GraphBuilder<V, E> {

    private Graph<V, E> graph;

    public GraphBuilder(Graph<V, E> graph) {
        this.graph = graph;
    }

    /**
     * @return the graph
     */
    public Graph<V, E> getGraph() {
        return graph;
    }

    /**
     * Add Edge to Graph
     *
     * @param v
     * @param v2
     * @param event
     */
    public void addEdge(V v, V v2, Event event) {
        graph.addEdge(v, v2, (E) new Edge("Form", event));
    }

    /**
     * Add Vertex to Graph
     *
     * @param v
     */
    public void addVertex(V v) {
        graph.addVertex(v);
    }

    /**
     * @param graph the graph to set
     */
    public void setGraph(Graph<V, E> graph) {
        this.graph = graph;
    }

}
