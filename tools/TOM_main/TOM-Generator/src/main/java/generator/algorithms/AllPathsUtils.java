package generator.algorithms;

import java.util.List;
import parsers.model.files.graph.elements.Edge;
import parsers.model.files.graph.elements.Path;
import parsers.model.files.graph.elements.Vertex;

/**
 * @author Marcelo Gonçalves
 */
public final class AllPathsUtils {

    private String finalNode;
    private final int maxEdgeVisit;
    private final int maxVertexVisit;
    private int rejectedPaths;

    public AllPathsUtils(String finalNode, int maxEdgeVisit, int maxVertexVisit) {
        this.finalNode = finalNode;

        if (finalNode == null) {
            this.finalNode = "";
        }

        this.maxEdgeVisit = maxEdgeVisit;
        this.maxVertexVisit = maxVertexVisit;
        this.rejectedPaths = 0;
    }

    public int getRejectedPaths() {
        return this.rejectedPaths;
    }

    /**
     * Given a path, and a Vertex
     * verifies if the path ends on the final state of the MODEL
     * Only if the final node is defined
     *
     * @param paths       - list of paths
     * @param nodePath    - path to check
     * @param currentNode - final vertex of the path
     */
    public void checkFinalNodePath(List<Path> paths, Path nodePath, Vertex currentNode) {
        if ("".equals(finalNode)) {
            paths.add(nodePath);
        } else {
            if (currentNode.getName().equals(finalNode)) {
                paths.add(nodePath);
            } else {
                this.rejectedPaths++;
            }
        }
    }

    /**
     * Given a path, a Vertex and a Edge
     * verifies the number of occurrence of the Objects
     *
     * @param p           - Path
     * @param edgeTarget  - Target Vertex of the edge
     * @param edgeSource  - Source Vertex of the edge
     * @param edge        - Edge
     * @return the right position to the switch
     */
    public int checkPath(Path p, Vertex edgeTarget, Vertex edgeSource, Edge edge) {
        int vTimes = 0;
        int eTimes = 0;
        int result = 0;

        List<Object> steps = p.getSteps();

        for (int i = 0; i < steps.size(); i++) {
            Object o = steps.get(i);

            if (o instanceof Vertex && o.equals(edgeTarget)) {
                vTimes++;
            } else if (o instanceof Edge) {
                Edge e = (Edge) o;
                if (e.getDst().equals(edge.getDst()) && edgeSource.equals(steps.get(i - 1))) {
                    eTimes++;
                }
            }
        }

        if (eTimes < this.maxEdgeVisit && vTimes <= this.maxVertexVisit) {
            if (vTimes == this.maxVertexVisit) {
                result = 1;
            } else if (vTimes < this.maxVertexVisit){
                result = 2;
            }
        }

        return result;
    }


}
