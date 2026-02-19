package generator.algorithms;

import org.jgrapht.Graph;
import parsers.model.files.graph.elements.Path;

import java.util.List;

/**
 * @author Raphael Rodrigues
 */
public interface ITraversalStrategy {
    /**
     * Execute the traversal algorithm in the graph
     *
     * @param g
     * @return
     */
    List<Path> execute(Graph g) throws OutOfMemoryError;

    List<Path> getOutOfMemoryPaths();

    int getOutOfMemoryPathsNumber();

}
