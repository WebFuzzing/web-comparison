package generator.algorithms;

import org.jgrapht.Graph;
import org.jgrapht.GraphPath;
import org.jgrapht.alg.shortestpath.BellmanFordShortestPath;
import parsers.model.files.graph.elements.Edge;
import parsers.model.files.graph.elements.Path;
import parsers.model.files.graph.elements.Vertex;
import utils.JGraphUtil;

import java.util.LinkedList;
import java.util.List;

/**
 * @param <V> Initial Vertex
 * @param <E> Final Vertex
 * @author Raphael Rodrigues
 *         BellmanFordShortest
 *         This Algoritm only gives us a PATH the shortest one
 */
public class MyBellmanFordShortestPath<V, E> implements ITraversalStrategy {

    private Graph<Vertex, Edge> graph;
    private V initial;
    private V end;
    private BellmanFordShortestPath<V, E> belford;

    public MyBellmanFordShortestPath(Graph graph, V initial, V end) {
        this.graph = graph;
        this.initial = initial;
        this.end = end;
        this.belford = new BellmanFordShortestPath<>(graph);
    }

    public MyBellmanFordShortestPath(Graph graph, String sInitial, String sEnd) {
        this.graph = graph;
        this.initial = (V) JGraphUtil.find(graph, sInitial);
        this.end = (V) JGraphUtil.find(graph, sEnd);
        this.belford = new BellmanFordShortestPath<>(graph);
    }

    /**
     * Give the path
     *
     * @return
     */
    public Path getPath() {
        Path path = new Path();

        GraphPath<V, E> graphPath = belford.getPath(initial, end);
        List<E> pathEdges = graphPath.getEdgeList();

        if (pathEdges == null) {
            return null;
        }

        Edge e = null;
        Vertex src;

        for (int i = 0; i < pathEdges.size(); i++) {
            src = graph.getEdgeSource((Edge) pathEdges.get(i));

            // Add vertex
            path.addStep(src);

            // Add the edge
            e = (Edge) pathEdges.get(i);

            path.addStep(e);
        }

        // Add last vertex
        src = graph.getEdgeTarget(e);
        path.addStep(src);

        return path;
    }

    /**
     * @return the belford
     */
    public BellmanFordShortestPath<V, E> getBelford() {
        return belford;
    }

    /**
     * @return the end
     */
    public V getEnd() {
        return end;
    }

    /**
     * @return the graph
     */
    public Graph<Vertex, Edge> getGraph() {
        return graph;
    }

    /**
     * @return the initial
     */
    public V getInitial() {
        return initial;
    }

    /**
     * @param belford the belford to set
     */
    public void setBelford(BellmanFordShortestPath<V, E> belford) {
        this.belford = belford;
    }

    /**
     * @param end the end to set
     */
    public void setEnd(V end) {
        this.end = end;
    }

    /**
     * @param graph the graph to set
     */
    public void setGraph(Graph<Vertex, Edge> graph) {
        this.graph = graph;
    }

    /**
     * @param initial the initial to set
     */
    public void setInitial(V initial) {
        this.initial = initial;
    }

    @Override
    public List<Path> execute(Graph g) {
        List<Path> paths = new LinkedList<>();
        paths.add(this.getPath());
        return paths;
    }

    @Override
    public List<Path> getOutOfMemoryPaths() {
        return null;
    }

    @Override
    public int getOutOfMemoryPathsNumber() {
        return -1;
    }
}
