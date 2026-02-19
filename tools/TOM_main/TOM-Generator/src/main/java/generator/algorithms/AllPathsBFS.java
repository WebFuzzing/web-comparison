package generator.algorithms;

import org.jgrapht.Graph;
import org.jgrapht.alg.util.Pair;
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
public class AllPathsBFS implements ITraversalStrategy {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    private final DirectedPseudograph<Vertex, Edge> graph;
    private final AllPathsUtils pathUtils;
    private final String startNode;

    private List<Path> outOfMemoryPaths;

    public AllPathsBFS(Graph<Vertex, Edge> graph, String startNode, String finalNode,
                       int maxVertexVisit, int maxEdgeVisit) {
        this.graph = (DirectedPseudograph<Vertex, Edge>) graph;
        this.pathUtils = new AllPathsUtils(finalNode, maxEdgeVisit, maxVertexVisit);
        this.startNode = startNode;
        this.outOfMemoryPaths = new ArrayList<>();
    }

    public AllPathsBFS(Graph<Vertex, Edge> graph, String startNode, int maxVertexVisit, int maxEdgeVisit) {
        this.graph = (DirectedPseudograph<Vertex, Edge>) graph;
        this.pathUtils = new AllPathsUtils("", maxEdgeVisit, maxVertexVisit);
        this.startNode = startNode;
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

        Queue<Pair> queue = new ArrayDeque();

        Path path = new Path();

        Pair p = new Pair(vStart, path);
        queue.add(p);
        try {
            while (!queue.isEmpty()) {
                Pair pair = queue.poll();

                Vertex node = (Vertex) pair.getFirst();
                Path nodePath = (Path) pair.getSecond();

                nodePath.addStep(node);

                Set<Edge> edges = graph.outgoingEdgesOf(node);

                if (edges.isEmpty()) {
                    pathUtils.checkFinalNodePath(paths, nodePath, node);
                } else {
                    scrollThroughEdges(paths, queue, edges, node, nodePath);
                }
            }
        } catch (OutOfMemoryError e) {
            this.outOfMemoryPaths = paths;
            throw new OutOfMemoryError();
        }

        return paths;
    }

    private void addToPath(List<Path> paths, Path nodePath, Queue queue, Vertex vTarget, Edge e, int caseInt){
        // Create a clone of the edge because because there may be multiple
        Edge eAux = new Edge(e);

        Path nodePathAux = new Path(nodePath);

        // Add the Edge to the current Path
        nodePathAux.addStep(eAux);

        if (caseInt == 1) {
            pathUtils.checkFinalNodePath(paths, nodePathAux, vTarget);
        } else {
            Pair p = new Pair(vTarget, nodePathAux);
            queue.add(p);
        }
    }

    private void scrollThroughEdges(List<Path> paths, Queue queue, Set<Edge> edges, Vertex node, Path nodePath) {

        for (Edge e : edges) {
            Vertex vTarget = graph.getEdgeTarget(e);

            int result = pathUtils.checkPath(nodePath, vTarget, node, e);

            switch (result) {
                case 0:
                    if (edges.size() == 1) {
                        pathUtils.checkFinalNodePath(paths, nodePath, node);
                    }
                    break;
                case 1:
                    addToPath(paths, nodePath, queue, vTarget, e, 1);
                    break;
                case 2:
                    addToPath(paths, nodePath, queue, vTarget, e, 2);
                    break;
                default:
                    break;
            }
        }
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