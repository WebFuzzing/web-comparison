package parsers.model.files.graph.elements;

import java.io.Serializable;

/**
 * @author raphael
 */
public class Interaction implements Serializable {

    private String name;
    private String typeOfInteraction;

    public Interaction(String name, String typeOfInteraction) {
        this.name = name;
        this.typeOfInteraction = typeOfInteraction;
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
     * @return the type of interaction
     */
    public String getType() {
        return typeOfInteraction;
    }

    /**
     * @param type the type of interaction to set
     */
    public void setType(String type) {
        this.typeOfInteraction = type;
    }
}
