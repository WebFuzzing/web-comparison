package parsers.model.files.graph.elements;

import java.io.Serializable;

/**
 * @author raphaelrodrigues
 */
public class Action implements Serializable {

    private String name;
    private String type;
    private String typeElement;

    public Action(String name, String type) {
        this.name = name;
        this.type = type;
    }

    public Action(String name, String type, String typeElement) {
        this.name = name;
        this.type = type;
        this.typeElement = typeElement;
    }

    public Action(Action a) {
        this.name = a.getName();
        this.type = a.getType();
        this.typeElement = a.getTypeElement();
    }

    /**
     * @return the name
     */
    public String getName() {
        return name;
    }

    /**
     * @return the type
     */
    public String getType() {
        return type;
    }

    /**
     * @param name the name to set
     */
    public void setName(String name) {
        this.name = name;
    }

    /**
     * @param type the type to set
     */
    public void setType(String type) {
        this.type = type;
    }

    /**
     * @return the typeElement
     */
    public String getTypeElement() {
        return typeElement;
    }

    /**
     * @param typeElement the typeElement to set
     */
    public void setTypeElement(String typeElement) {
        this.typeElement = typeElement;
    }

    public boolean myEquals(Action a){
        return getName().equals(a.getName()) && getType().equals(a.getType()) && getTypeElement().equals(a.getTypeElement());
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) {
            return true;
        }
        if (o == null || getClass() != o.getClass()) {
            return false;
        }

        Action action = (Action) o;

        if (name != null ? !name.equals(action.name) : action.name != null) {
            return false;
        }
        if (type != null ? !type.equals(action.type) : action.type != null) {
            return false;
        }
        return typeElement != null ? typeElement.equals(action.typeElement) : action.typeElement == null;
    }

}
