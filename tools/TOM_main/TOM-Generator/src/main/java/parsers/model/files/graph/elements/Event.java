package parsers.model.files.graph.elements;

import java.io.Serializable;

/**
 * @author raphaelrodrigues
 */
public abstract class Event implements Serializable {

    private String id;
    private String modelName;

    public Event(String id, String modelName) {
        this.id = id;
        this.modelName = modelName;
    }

    public Event(Event e) {
        this.id = e.getId();
        this.modelName = e.getModelName();
    }

    /**
     * @return the id
     */
    public String getId() {
        return id;
    }

    /**
     * @param id the id to set
     */
    public void setId(String id) {
        this.id = id;
    }

    /**
     * @return the modelName
     */
    public String getModelName() {
        return modelName;
    }

    /**
     * @param modelName the modelName to set
     */
    public void setModelName(String modelName) {
        this.modelName = modelName;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) {
            return true;
        }
        if (o == null || getClass() != o.getClass()) {
            return false;
        }

        Event event = (Event) o;

        if (id != null ? !id.equals(event.id) : event.id != null) {
            return false;
        }
        return modelName != null ? modelName.equals(event.modelName) : event.modelName == null;
    }
}
