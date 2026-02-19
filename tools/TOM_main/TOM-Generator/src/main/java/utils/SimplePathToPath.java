package utils;

import org.jgrapht.Graph;
import parsers.model.files.graph.elements.Edge;
import parsers.model.files.graph.elements.Path;
import parsers.model.files.graph.elements.SimplePath;
import parsers.model.files.graph.elements.Vertex;
import java.util.ArrayList;
import java.util.LinkedList;
import java.util.List;

/**
 * @author raphaelrodrigues
 */
public final class SimplePathToPath {

    private SimplePathToPath() {
        throw new IllegalAccessError("Utility class");
    }

    public static Path convert(SimplePath<Vertex> simplePath) {

        Path p = new Path();
        for (Vertex v : simplePath.nodes()) {
            p.addStep(v);
        }

        return p;
    }

    public static List<Path> convert(List<SimplePath<Vertex>> lSimpPath) {
        List<Path> paths = new ArrayList<>();
        Path p;

        for (SimplePath<Vertex> v : lSimpPath) {
            p = SimplePathToPath.convert(v);
            paths.add(p);
        }

        return paths;
    }

    public static List<Path> getAbstractPaths(Graph graph, List<SimplePath<Vertex>> vertexPaths) {
        List<Path> paths = new LinkedList<>();
        Path path;

        for (SimplePath<Vertex> simplePath : vertexPaths) {
            path = new Path();
            List<Vertex> e = simplePath.nodes();
            path.addStep(e.get(0));
            for (int h = 1; h <= e.size() - 1; h++) {
                Edge aux = (Edge) graph.getEdge(e.get(h - 1), e.get(h));
                //path.addStep(aux);
                path.addStep(aux.getEvent());
                path.addStep(e.get(h));
            }
            paths.add(path);
        }
        return paths;
    }

}
