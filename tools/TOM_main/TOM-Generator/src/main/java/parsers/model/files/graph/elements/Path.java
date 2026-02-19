package parsers.model.files.graph.elements;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.LinkedList;
import java.util.List;

/**
 * Represent a path of the graph
 * Theres a list of Steps
 *
 * @author raphaelrodrigues
 */
public class Path implements Serializable {

    private List<Object> steps;

    public Path() {
        this.steps = new ArrayList<>();
    }

    public Path(Path path) {

        this.steps = new ArrayList<>();

        for (Object obj : path.getSteps()){
            if (obj instanceof Vertex) {
                this.steps.add(new Vertex((Vertex) obj));
            } else if (obj instanceof Edge) {
                this.steps.add(new Edge((Edge) obj));
            }
        }

        this.steps = new ArrayList<>(path.getSteps());
    }

    public void addStep(Object obj) {
        this.steps.add(obj);
    }

    /**
     * Compare if the path contains another
     *
     * @param obj Path to compare
     * @return
     */
    public boolean containsAll(Path obj) {
        Object[] stepsArray = steps.toArray();
        Object[] objArray = obj.getSteps().toArray();

        for (int i = 0; i < stepsArray.length && i < objArray.length; i++) {
            if (stepsArray[i] instanceof Vertex && objArray[i] instanceof Vertex) {
                Vertex v1 = (Vertex) stepsArray[i];
                Vertex v2 = (Vertex) objArray[i];

                if (!v1.equals(v2)) {
                    return false;
                }

            } else if (stepsArray[i] instanceof Edge && objArray[i] instanceof Edge) {

                Edge e1 = (Edge) stepsArray[i];
                Edge e2 = (Edge) objArray[i];

                if (!e1.equals(e2)) {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * Determine if <code>this</code> path is a subpath of <code>other</code>.
     *
     * @param other The (possible) super-path
     * @return
     */
    public boolean subpathOf(Path other) {
        if (this.steps.size() > other.getSteps().size())
            return false;
        int iter = other.getSteps().size() - this.steps.size() + 1;
        for (int i = 0; i < iter; i++) {
            boolean match = true;
            for (int j = 0; j < this.steps.size(); j++) {
                if (!this.steps.get(j).equals(other.getSteps().get(i + j))) {
                    match = false;
                    break;
                }
            }
            if (match)
                return true;
        }
        return false;
    }

    public Object getStep(int index) {
        return this.steps.get(index);
    }

    public int statisticsForTests() {
        return this.steps.size();
    }

    public String statistics() {
        String sb = "\n";
        int numVertex = 0;
        int numForms = 0;
        Form fm;
        Call link;
        Vertex v;
        Event e;
        // int num_action_form[];

        int num_calls = 0;
        for (Object s : getSteps()) {
            // if object a vertex
            if (s instanceof Vertex) {
                v = (Vertex) s;
                System.out.print(v.getClass() + " ");
                numVertex++;
            } else if (s instanceof Event) {
                e = (Event) s;
                System.out.print(e.getClass() + " ");
                if (e instanceof Form) {
                    fm = (Form) e;
                    //num_action_form[num_forms] = fm.getActions().size() ;
                    numForms++;

                } else if (e instanceof Call) {
                    num_calls++;
                }
            }
        }
        sb += "Num Vertex " + numVertex + "\n";
        sb += "Num Calls " + num_calls + "\n";
        sb += "Num Forms " + numForms + "\n";

        return sb;

    }

    /**
     * Go through each step
     * Each step can be a Vertex OR a Event
     * An Event can be a Form OR a Call
     *
     * @return A string representation of the path
     */
    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();

        for (Object s : getSteps()) {
            if (s instanceof Vertex) {
                Vertex v = (Vertex) s;
                sb.append("[" + v.getName() + "] " + " -> ");
            } else if (s instanceof Edge) {
                Form fm;
                Call link;
                Edge edge = (Edge) s;
                Event e = edge.getEvent();
                if (e instanceof Form) {
                    fm = (Form) e;
                    sb.append(" FORM " + fm.getId() + ", ");
                } else if (e instanceof Call) {
                    link = (Call) e;
                    sb.append("CALL " + link.getModelName() + ", ");
                }
            }
        }
        return sb.toString() + "\n";
    }

    /**
     * @return A clone of the steps
     */
    public List<Object> clonedSteps() {
        List<Object> stepsAux = new LinkedList<>();
        for (Object o : this.steps) {
            if (o instanceof Vertex) {
                Vertex v = (Vertex) o;
                stepsAux.add(v);
            } else if (o instanceof Edge) {
                Edge edge = (Edge) o;
                stepsAux.add(new Edge(edge));
            }
        }
        return stepsAux;
    }

    /**
     * @param steps the steps to set
     */
    public void setSteps(List<Object> steps) {
        this.steps = steps;
    }

    public List<Object> getSteps() {
        return steps;
    }

    public void remove(Object o) {
        this.steps.remove(o);
    }

    public void removeFromEnd(Object o) {
        for (int i = this.steps.size() - 1; i >= 0; i--) {
            if (o.hashCode() != this.steps.get(i).hashCode()) {
                this.steps.remove(o);
                break;
            }
        }
    }

    public void removeMultiple(Path p) {
        for (Object o : p.getSteps()) {
            this.steps.remove(o);
        }
    }
}

