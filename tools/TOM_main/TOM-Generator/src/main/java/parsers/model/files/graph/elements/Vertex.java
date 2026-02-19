package parsers.model.files.graph.elements;

import java.io.Serializable;

/**
 * @author raphaelrodrigues
 */
public class Vertex implements Serializable, Comparable<Vertex> {
    private String name;
    private Validations validations;
    private Vertex parent;
    private Edge edgeFromParent;

    public Vertex(String name) {
        this.name = name;
        this.parent = null;
        this.edgeFromParent = null;
    }

    public Vertex(Vertex v) {
        this.name = v.getName();
        this.parent = v.getParent();
        this.edgeFromParent = v.edgeFromParent;
        this.validations = v.getValidations();
    }

    /**
     * Verify if this Vertex has validations to do
     *
     * @return If empty return false else return true
     */
    public boolean hasValidations() {

        if (this.getValidations() != null) {
            return !this.getValidations().getValidationList().isEmpty();
        }

        return false;
    }

    /**
     * @return the name
     */
    public String getName() {
        return name;
    }

    /**
     * @param name the name to set
     */
    public void setName(String name) {
        this.name = name;
    }

    /**
     * @return the validations
     */
    public Validations getValidations() {
        return validations;
    }

    /**
     * @param validations the validations to set
     */
    public void setValidations(Validations validations) {
        this.validations = validations;
    }

    public void setParentData(Vertex v, Edge e) {
        this.parent = v;
        this.edgeFromParent = e;
    }

    public Vertex getParent() {
        return this.parent;
    }

    public Edge getEdgeFromParent() {
        return this.edgeFromParent;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) {
            return true;
        }
        if (o == null || getClass() != o.getClass()) {
            return false;
        }

        Vertex vertex = (Vertex) o;

        return name.equals(vertex.name);
    }

    @Override
    public int hashCode() {
        return name.hashCode();
    }

    /**
     * Compare two Vertex by it's vertex number
     */
    @Override
    public int compareTo(Vertex o) {
        if (name.equals(o.name)) {
            return 0;
        } else {
            return 1;
        }
    }
}
