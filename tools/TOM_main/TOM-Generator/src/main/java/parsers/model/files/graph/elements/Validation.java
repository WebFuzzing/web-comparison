package parsers.model.files.graph.elements;

import java.io.Serializable;

/**
 * @author raphaelrodrigues
 *         <p>
 *         Possible Types
 *         element_displayed? -> browser.find_element(:id => 4).displayed?
 *         exists? -> browser.find_element(:name, "checkthebox")
 *         default_value -> browser.find_element(:id, 2).text =~ /hoo/
 */
public class Validation implements Serializable {

    private String type;
    private String id;

    public Validation(String type, String id) {
        this.type = type;
        this.id = id;
    }

    public Validation(Validation v) {
        this.type = v.getType();
        this.id = v.getId();
    }

    /**
     * @return the id
     */
    public String getId() {
        return id;
    }

    /**
     * @return the type
     */
    public String getType() {
        return type;
    }

    /**
     * @param id the id to set
     */
    public void setId(String id) {
        this.id = id;
    }

    /**
     * @param type the type to set
     */
    public void setType(String type) {
        this.type = type;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) {
            return true;
        }
        if (o == null || getClass() != o.getClass()) {
            return false;
        }

        Validation that = (Validation) o;

        if (!type.equals(that.type)) {
            return false;
        }
        return id.equals(that.id);
    }

    @Override
    public int hashCode() {
        int result = type.hashCode();
        result = 31 * result + id.hashCode();
        return result;
    }
}
