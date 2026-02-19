package utils;

import org.jgrapht.*;
import org.jgrapht.ext.ComponentNameProvider;
import org.jgrapht.ext.DOTExporter;
import org.jgrapht.graph.DirectedPseudograph;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import parsers.model.files.graph.elements.Edge;
import parsers.model.files.graph.elements.Event;
import parsers.model.files.graph.elements.Path;
import parsers.model.files.graph.elements.Vertex;
import generator.algorithms.MyBellmanFordShortestPath;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.lang.reflect.Constructor;
import java.util.*;

/**
 * @author raphaelrodrigues
 */
public final class JGraphUtil {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    private JGraphUtil() {
        throw new IllegalAccessError("Utility class");
    }

    /**
     * Find a vertex by a key in a graph
     *
     * @param graph The graph
     * @param key   The key
     * @return node, or <code>null</code> if no matching node was found.
     */
    public static Vertex find(Graph<Vertex, ?> graph, String key) {
        for (Vertex node : graph.vertexSet()) {
            String nodeStr = node.getName();
            if (nodeStr.equals(key)) {
                return node;
            }
        }
        return null;
    }

    /**
     * Check if a graph is directed
     *
     * @param <V>
     * @param <E>
     * @param graph
     * @return
     */
    public static <V, E> boolean isDirected(Graph<V, E> graph) {
        return graph instanceof DirectedGraph;
    }

    /**
     * @param <V>
     * @param <E>
     * @param graph
     * @return
     */
    public static <V, E> int maxEdges(Graph<V, E> graph) {
        int vs = graph.vertexSet().size();

        if (graph instanceof UndirectedGraph) {
            return vs * (vs - 1) / 2;
        } else if (graph instanceof DirectedGraph) {
            return vs * (vs - 1);
        } else {
            throw new RuntimeException("Unknown graph type");
        }
    }

    /**
     * Create a copy of the graph
     *
     * @param <V>
     * @param <E>
     * @param <G>
     * @param graph
     * @return
     */
    public static <V, E, G extends Graph<V, E>> G clone(G graph) {
        try {
            G cloned;

            EdgeFactory ef = graph.getEdgeFactory();
            try {
                // Lookup a constructor which takes an edge-factory as parameter
                Constructor efCon = graph.getClass().getConstructor(EdgeFactory.class);
                cloned = (G) efCon.newInstance(ef);
            } catch (NoSuchMethodException me) {
                // Lookup a constructor which takes an edge-type as parameter
                Constructor etCon = graph.getClass().getConstructor(Class.class);
                Object edge = ef.createEdge(null, null);
                cloned = (G) etCon.newInstance(edge.getClass());
            }

            // Add all data to the cloned from the original
            Graphs.addGraph(cloned, graph);

            return cloned;
        } catch (Exception e) {
            throw new RuntimeException("Error cloning: " + e.getMessage(), e);
        }
    }

    public static List<Path> compare(Graph newGraph, Graph previousGraph, String initialNode) {

        List<Path> differences = new ArrayList<>();

        DirectedPseudograph<Vertex, Edge> nG = (DirectedPseudograph<Vertex, Edge>) newGraph;
        DirectedPseudograph<Vertex, Edge> pG = (DirectedPseudograph<Vertex, Edge>) previousGraph;

        TreeSet<Vertex> newVertexSet = new TreeSet<>();
        newVertexSet.addAll(newGraph.vertexSet());

        TreeSet<Vertex> previousVertexSet = new TreeSet<>();
        previousVertexSet.addAll(previousGraph.vertexSet());

        for (Vertex nV : newVertexSet) {

            Vertex toRemove = null;

            for (Vertex pV : previousVertexSet) {

                if (nV.getName().equals(pV.getName())) {

                    toRemove = pV;

                    Set<Edge> nvEdges = nG.outgoingEdgesOf(nV);
                    Set<Edge> pvEdges = pG.outgoingEdgesOf(pV);

                    if (nvEdges.size() < pvEdges.size()) {
                        // Add the Vertex shortest path to the list
                        MyBellmanFordShortestPath<Vertex, Edge> ap1 = new MyBellmanFordShortestPath<>(newGraph, initialNode, nV.getName());
                        List<Path> path = ap1.execute(newGraph);
                        differences.add(path.get(0));
                        //return differences;
                    } else {

                        for (Edge nE : nvEdges) {
                            Event newEvent = nE.getEvent();

                            boolean exists = false;

                            for (Edge pE : pvEdges) {
                                Event previousEvent = pE.getEvent();
                                if (nE.getDst().equals(pE.getDst()) && newEvent.getId().equals(previousEvent.getId())
                                        && newEvent.getModelName().equals(previousEvent.getModelName())) {
                                    // The Edge exists in the previous graph
                                    exists = true;
                                    break;
                                }
                            }
                            if (!exists) {
                                // Add the Edge shortest path  to the list
                                MyBellmanFordShortestPath<Vertex, Edge> ap1 = new MyBellmanFordShortestPath<>(newGraph, initialNode, pV.getName());
                                List<Path> path = ap1.execute(newGraph);
                                path.get(0).addStep(nE);
                                differences.add(path.get(0));
                                //return differences;
                            }
                        }
                        break;
                    }
                }
            }
            if (toRemove != null) {
                previousVertexSet.remove(toRemove);
            }
        }
        return differences;
    }

    public static void generateFile(String targetDirectory, String fileName, Graph g) throws IOException {

        ComponentNameProvider vertexNames = (ComponentNameProvider<Vertex>) component -> component.getName();

        ComponentNameProvider edgeNames = (ComponentNameProvider<Edge>) component -> component.getEvent().getModelName();

        DOTExporter exporter = new DOTExporter(vertexNames, vertexNames, edgeNames);

        new File(targetDirectory).mkdirs();
        if(fileName != ""){
            exporter.exportGraph(g, new FileWriter(targetDirectory + fileName + ".dot"));
        } else {
            exporter = new DOTExporter();
            exporter.exportGraph(g, new FileWriter(targetDirectory + "Graph.dot"));
        }
    }

    public static void writePathsToFile(String targetDirectory, String fileName, List<Path> paths) throws IOException {
        new File(targetDirectory).mkdirs();
        FileWriter writer = null;
        try {
            writer = new FileWriter(targetDirectory + fileName + ".txt");
            for (Path p : paths) {
                writer.write(p.toString() + "\n");
            }
        } catch (IOException e) {
            logger.error(e.getMessage());
        } finally {
            if (writer != null)
                writer.close();
        }
    }
}
