package parsers.model.files.graph.elements;

import java.util.ArrayList;
import java.util.List;

/**
 * A path from node ni to nj is simple if no node appears more than once,
 * except possibly the first and last nodes are the same
 * No internal loops
 * A loop is a simple path
 *
 * @param <V>
 * @author raphaelrodrigues
 */

public class SimplePath<V> {
    private ArrayList<V> nodes;

    public SimplePath(V start) {
        this.nodes = new ArrayList<>();
        this.nodes.add(start);
    }

    public SimplePath() {
        this.nodes = new ArrayList<>();
    }

    public V start() {
        return nodes.get(0);
    }

    public boolean has(V node) {
        return nodes.contains(node);
    }

    public List<V> nodes() {
        return nodes;
    }

    /**
     * Determine if <code>this</code> path is a subpath of <code>other</code>.
     *
     * @param other The (possible) super-path
     * @return
     */
    public boolean subpathOf(SimplePath<V> other) {
        if (this.nodes.size() > other.nodes.size())
            return false;
        int iter = other.nodes.size() - this.nodes.size() + 1;
        for (int i = 0; i < iter; i++) {
            boolean match = true;
            for (int j = 0; j < this.nodes.size(); j++) {
                if (!this.nodes.get(j).equals(other.nodes.get(i + j))) {
                    match = false;
                    break;
                }
            }
            if (match)
                return true;
        }
        return false;
    }

    public SimplePath<V> add(V node) {
        SimplePath<V> added = new SimplePath<>();
        added.nodes.addAll(this.nodes);
        added.nodes.add(node);
        return added;
    }

    public V last() {
        return nodes.get(nodes.size() - 1);
    }

    @Override
    public String toString() {
        return "SimplePath { " +
                "nodes = " + nodes +
                '}';
    }
}
