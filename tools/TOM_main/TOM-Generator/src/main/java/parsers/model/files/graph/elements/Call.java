package parsers.model.files.graph.elements;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

/**
 * @author raphaelrodrigues
 */
public class Call extends Event implements Serializable {

    private transient List<Interaction> steps;

    private Validations validations;

    // Can be a click, double click or mouse hover
    private String typeOfCall;

    public Call(String id, String modelName, String typeOfCall) {
        super(id, modelName);
        this.steps = new ArrayList();
        this.typeOfCall = typeOfCall;
    }

    public Call(String id, String modelName, String typeOfCall, List<Interaction> steps) {
        super(id, modelName);
        this.steps = steps;
        this.typeOfCall = typeOfCall;
    }

    public Call(Call call) {
        super(call.getId(), call.getModelName());
        this.steps = call.getSteps();
        this.typeOfCall = call.getTypeOfCall();
    }

    public void addSteps(List<Interaction> steps) {
        this.setSteps(steps);
    }

    /**
     * Verify if the Vertex has validations to do
     */
    public boolean hasSteps() {
        // If empty return false else return true
        return !this.getSteps().isEmpty();
    }

    /**
     * @return the steps
     */
    public List<Interaction> getSteps() {
        return steps;
    }

    /**
     * @param steps the steps to set
     */
    public void setSteps(List<Interaction> steps) {
        this.steps = steps;
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

    /**
     * @return the typeOfCall
     */
    public String getTypeOfCall() {
        return typeOfCall;
    }

    /**
     * @param typeOfCall the typeOfCall to set
     */
    public void setTypeOfCall(String typeOfCall) {
        this.typeOfCall = typeOfCall;
    }

}
