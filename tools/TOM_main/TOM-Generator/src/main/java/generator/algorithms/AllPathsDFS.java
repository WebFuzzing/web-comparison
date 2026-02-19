package generator.algorithms;

import org.jgrapht.Graph;
import org.jgrapht.graph.DirectedPseudograph;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import parsers.model.files.graph.elements.Edge;
import parsers.model.files.graph.elements.Path;
import parsers.model.files.graph.elements.Vertex;
import utils.DefaultValues;
import utils.JGraphUtil;

import java.util.*;

/**
 * @author Marcelo Gonçalves
 */
public class AllPathsDFS implements ITraversalStrategy {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    private final DirectedPseudograph<Vertex, Edge> graph;
    private final AllPathsUtils pathUtils;
    private final String startNode;
    private Deque stack;

    private List<Path> outOfMemoryPaths;

    public AllPathsDFS(Graph<Vertex, Edge> graph, String startNode, String finalNode, int maxVertexVisit, int maxEdgeVisit) {
        this.graph = (DirectedPseudograph<Vertex, Edge>) graph;
        this.startNode = startNode;
        this.pathUtils = new AllPathsUtils(finalNode, maxEdgeVisit, maxVertexVisit);
        this.outOfMemoryPaths = new ArrayList<>();
    }

    public AllPathsDFS(Graph<Vertex, Edge> graph, String startNode, int maxVertexVisit, int maxEdgeVisit) {
        this.graph = (DirectedPseudograph<Vertex, Edge>) graph;
        this.startNode = startNode;
        this.pathUtils = new AllPathsUtils("", maxEdgeVisit, maxVertexVisit);
        this.outOfMemoryPaths = new ArrayList<>();
    }

    public int getRejectedPaths() {
        return pathUtils.getRejectedPaths();
    }

    private List<Path> allPath() {
        return allPathsIterative();
    }

    private List<Path> allPathsIterative() {
        List<Path> paths = new ArrayList<>();

        Vertex vStart = JGraphUtil.find(graph, startNode);

        if (vStart == null) {
            logger.error("Vertex not found!");
            return paths;
        }

        stack = new ArrayDeque<>();

        stack.push(vStart);

        try {
            while (!stack.isEmpty()) {
                Vertex nodeCopy = (Vertex) stack.pop();

                Vertex node = JGraphUtil.find(graph, nodeCopy.getName());

                Set<Edge> edges = graph.outgoingEdgesOf(node);

                if (edges.isEmpty()) {
                    Path path = buildPath(nodeCopy);
                    pathUtils.checkFinalNodePath(paths, path, nodeCopy);
                } else {
                    scrollThroughEdges(paths, edges, nodeCopy);
                }
            }
        } catch (OutOfMemoryError e) {
            this.outOfMemoryPaths = paths;
            throw new OutOfMemoryError();
        }

        return paths;
    }

    private void scrollThroughEdges(List<Path> paths, Set<Edge> edges, Vertex node) {

        Path currentPath = buildPath(node);

        for (Edge e : edges) {
            Vertex vTarget = graph.getEdgeTarget(e);
            Vertex targetCopy = new Vertex(vTarget.getName());

            Vertex vSource = node;

            targetCopy.setParentData(vSource, e);

            int result = pathUtils.checkPath(currentPath, targetCopy, vSource, e);

            switch (result) {
                case 0:
                    if (edges.size() == 1) {
                        Path path = buildPath(vSource);
                        pathUtils.checkFinalNodePath(paths, path, vSource);
                    }
                    break;
                case 1:
                    Path path = buildPath(targetCopy);
                    pathUtils.checkFinalNodePath(paths, path, targetCopy);
                    break;
                case 2:
                    stack.push(targetCopy);
                    break;
                default:
                    break;
            }
        }
    }

    private Path buildPath(Vertex vertex) {
        Deque arrayDeque = new ArrayDeque();

        // Push the path to the stack
        while (vertex != null) {
            Vertex v = JGraphUtil.find(graph, vertex.getName());
            arrayDeque.push(v);

            if (vertex.getEdgeFromParent() != null) {
                arrayDeque.push(vertex.getEdgeFromParent());
            }

            vertex = vertex.getParent();
        }

        Path path = new Path();
        // Create the path
        while (!arrayDeque.isEmpty()) {
            path.addStep(arrayDeque.pop());
        }

        return path;
    }

    /**
     * Execute the algorithm
     *
     * @param g
     * @return
     */
    @Override
    public List<Path> execute(Graph g) throws OutOfMemoryError {
        return this.allPath();
    }

    @Override
    // Paths limit: 100000
    public List<Path> getOutOfMemoryPaths() {
        List<Path> result = new ArrayList<>();

        if (this.outOfMemoryPaths.size() <= 100000) {
            return this.outOfMemoryPaths;
        }

        for (int i = 0; i < 100000; i++) {
            result.add(this.outOfMemoryPaths.get(i));
        }

        return result;
    }

    @Override
    public int getOutOfMemoryPathsNumber(){
        return this.outOfMemoryPaths.size();
    }
}