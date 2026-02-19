package parsers.model.files.graph.elements;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

/**
 * @author raphael
 */
public class Validations implements Serializable {

    private List<Validation> validationsList;

    public Validations() {
        this.validationsList = new ArrayList<>();
    }

    public Validations(Validations val) {
        this.validationsList = new ArrayList<>();
        for (Validation v : val.getValidationList()) {
            this.validationsList.add(new Validation(v));
        }
    }

    public void addValidation(String type, String id) {
        Validation v = new Validation(type, id);
        this.getValidationList().add(v);
    }

    /**
     * @return the validationList
     */
    public List<Validation> getValidationList() {
        return validationsList;
    }

    /**
     * @param validationsList the validationsList to set
     */
    public void setValidationList(List<Validation> validationsList) {
        this.validationsList = validationsList;
    }
}
