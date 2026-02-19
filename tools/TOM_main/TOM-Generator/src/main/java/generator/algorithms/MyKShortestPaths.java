package generator.algorithms;

import org.jgrapht.Graph;
import org.jgrapht.GraphPath;
import org.jgrapht.alg.shortestpath.KShortestPaths;
import parsers.model.files.graph.elements.Edge;
import parsers.model.files.graph.elements.Path;
import utils.JGraphUtil;

import java.util.LinkedList;
import java.util.List;

/**
 * The algorithm determines the k shortest simple paths in increasing order of weight.
 * Weights can be negative (but no negative cycle is allowed), and paths can be constrained
 * by a maximum number of edges. Multigraphs are allowed.
 *
 * @param <V>
 * @param <E>
 * @author Raphael Rodrigues
 */
public class MyKShortestPaths<V, E> implements ITraversalStrategy {

    private Graph<V, E> graph;
    private V initial;
    private V end;
    private int nPaths;
    private KShortestPaths<V, E> ks;

    public MyKShortestPaths(Graph graph, V initial, V end, int nPaths) {
        this.graph = graph;
        this.initial = initial;
        this.end = end;
        this.nPaths = nPaths;
        this.ks = new KShortestPaths(graph, nPaths);
    }

    public MyKShortestPaths(Graph graph, String initNode, String endNode, int nPaths) {
        this.graph = graph;
        this.initial = (V) JGraphUtil.find(graph, initNode);
        this.end = (V) JGraphUtil.find(graph, endNode);
        this.nPaths = nPaths;
        this.ks = new KShortestPaths(graph, nPaths);
    }

    private LinkedList<List<V>> getListVertexPath() {
        LinkedList<List<V>> results = new LinkedList<>();

        List<GraphPath<V, E>> f = ks.getPaths(this.initial, this.end);

        if (f == null) {
            return null;
        }

        for (GraphPath<V, E> aux1 : f) {
            List<V> list = aux1.getVertexList();
            results.add(list);
        }

        return results;
    }

    public List<Path> getAbstractPaths() {
        List<Path> paths = new LinkedList<>();
        Path path;

        if (this.getListVertexPath() == null) {
            return null;
        }

        LinkedList<List<V>> list = getListVertexPath();

        for (List<V> e : list) {
            path = new Path();

            path.addStep(e.get(0));

            int size = e.size();

            for (int h = 1; h < size; h++) {
                V v = e.get(h);

                Edge aux = (Edge) graph.getEdge(e.get(h - 1), v);

                path.addStep(aux);

                path.addStep(v);
            }
            paths.add(path);
        }
        return paths;
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
    public Graph<V, E> getGraph() {
        return graph;
    }

    /**
     * @return the initial
     */
    public V getInitial() {
        return initial;
    }

    /**
     * @return the ks
     */
    public KShortestPaths<V, E> getKs() {
        return ks;
    }

    /**
     * @return the nPaths
     */
    public int getnPaths() {
        return nPaths;
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
    public void setGraph(Graph<V, E> graph) {
        this.graph = graph;
    }

    /**
     * @param initial the initial to set
     */
    public void setInitial(V initial) {
        this.initial = initial;
    }

    /**
     * @param ks the ks to set
     */
    public void setKs(KShortestPaths<V, E> ks) {
        this.ks = ks;
    }

    /**
     * @param nPaths the nPaths to set
     */
    public void setnPaths(int nPaths) {
        this.nPaths = nPaths;
    }

    @Override
    public List<Path> execute(Graph g) {
        return this.getAbstractPaths();
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
