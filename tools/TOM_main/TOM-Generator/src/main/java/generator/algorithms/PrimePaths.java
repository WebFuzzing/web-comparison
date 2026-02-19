package generator.algorithms;

import org.jgrapht.DirectedGraph;
import org.jgrapht.Graph;
import parsers.model.files.graph.elements.Path;
import parsers.model.files.graph.elements.SimplePath;
import parsers.model.files.graph.elements.Vertex;
import utils.JGraphUtil;
import utils.SimplePathToPath;

import java.util.ArrayList;
import java.util.LinkedList;
import java.util.List;

/**
 * What is a prime path?
 * A simple path that does not appear as a proper subpath of any other simple path
 * A path from node nj to node nk is prime if
 * It is a simple path
 * It is not a proper sub-path of any other simple path
 * It is a maximal length simple path
 *
 * @param <V>
 * @param <E>
 * @author Raphael Rodrigues
 */
public class PrimePaths<V, E> implements ITraversalStrategy {

    private final DirectedGraph<Vertex, E> graph;

    public PrimePaths(DirectedGraph<Vertex, E> graph) {
        this.graph = graph;
    }


    public List<SimplePath<Vertex>> allPaths() {
        return this.calculate();
    }

    public List<SimplePath<Vertex>> allPathsFromInit(String initial) {
        List<SimplePath<Vertex>> found = this.calculate();

        LinkedList<SimplePath<Vertex>> aux = new LinkedList<>(found);

        for (SimplePath<Vertex> p1 : aux) {
            if (!p1.nodes().get(0).getName().equals(initial)) {
                found.remove(p1);
            }
        }
        return found;
    }

    private List<SimplePath<Vertex>> calculate() {

        // Q is the queue of "in process" paths
        LinkedList<SimplePath<Vertex>> qeue = new LinkedList<>();

        // F is the found simple paths
        LinkedList<SimplePath<Vertex>> found = new LinkedList<>();

        LinkedList<SimplePath<Vertex>> foundedWithInit = new LinkedList<>();

        Vertex init = JGraphUtil.find(graph, "root");

        // initialize queue Get all vertex in the graph
        for (Vertex v : graph.vertexSet()) {
            qeue.add(new SimplePath<>(v));
        }

        while (!qeue.isEmpty()) {
            //get the first elem in the queue
            SimplePath<Vertex> p = qeue.removeFirst();

            Vertex last = p.last();
            if (graph.outDegreeOf(last) == 0) {
                found.add(p);
                continue;
            }
            //iterate in all sucessors of last
            for (E outgoing : graph.outgoingEdgesOf(p.last())) {

                Vertex target = graph.getEdgeTarget(outgoing);
                //add the vertex to SimplePath
                SimplePath<Vertex> np = p.add(target);
                if (p.has(target)) {
                    if (p.start().equals(target))
                        found.add(np);
                    else
                        found.add(p);
                } else {
                    qeue.add(np);
                }
            }
        }

        // get all sub-paths
        List<SimplePath<Vertex>> prune = new ArrayList<>();
        for (SimplePath<Vertex> p : found) {
            for (SimplePath<Vertex> q : found) {
                if (p == q)
                    continue;
                if (p.subpathOf(q))
                    prune.add(p);
            }
        }

        //remove the PRUNE Paths
        for (SimplePath<Vertex> p : prune) {
            found.remove(p);
        }

        return found;
    }

    @Override
    public List<Path> execute(Graph g) {
        List<SimplePath<Vertex>> prune = this.calculate();
        return SimplePathToPath.getAbstractPaths(graph, prune);
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