package parsers.model.files.graph.elements;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

/**
 * @author raphaelrodrigues
 */
public class ErrorVertex implements Serializable {

    private List<Validation> validations;
    private String target;

    public ErrorVertex(String target) {
        this.target = target;
        this.validations = new ArrayList<>();
    }

    public void addValidation(String type, String id) {
        Validation v = new Validation(type, id);
        this.getValidations().add(v);
    }

    /**
     * @return the validations
     */
    public List<Validation> getValidations() {
        return validations;
    }

    /**
     * @param validations the validations to set
     */
    public void setValidations(List<Validation> validations) {
        this.validations = validations;
    }


    public String validation2String() {

        StringBuilder sb = new StringBuilder();

        for (Validation v : this.validations) {
            sb.append(" " + v.getId());
        }

        return sb.toString();
    }

    @Override
    public String toString() {
        return "ErrorVertex{ " + "validations = " + validation2String() + ", target = " + target + '}';
    }

}
