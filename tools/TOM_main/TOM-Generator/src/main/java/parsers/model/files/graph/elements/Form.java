package parsers.model.files.graph.elements;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

/**
 * @author raphaelrodrigues
 */
public class Form extends Event implements Serializable {

    private ArrayList<Action> actions;
    private String submit;
    private String type;
    private List<Validation> validations;
    private String condition;
    private ErrorVertex error;

    public Form(String id, String modelName) {
        super(id, modelName);
        this.actions = new ArrayList();
    }

    public Form(String id, String modelName, List<Action> actions, String submit,
                String type, String condition, List<Validation> validations) {
        super(id, modelName);
        this.actions = new ArrayList();
        for (Action a : actions){
            this.actions.add(new Action(a));
        }
        this.submit = submit;
        this.type = type;
        this.condition = condition;
        this.validations = validations;
    }

    public Form(String id, String modelName, List<Action> actions, String submit,
                String type, String condition, ErrorVertex error, List<Validation> validations) {
        super(id, modelName);
        this.actions = new ArrayList();
        for (Action a : actions){
            this.actions.add(new Action(a));
        }
        this.submit = submit;
        this.type = type;
        this.condition = condition;
        this.error = error;
        this.validations = validations;
    }

    public void addAction(String name, String type) {
        Action action = new Action(name, type);
        this.actions.add(action);
    }

    /**
     * @return all the actions
     */
    public List<Action> getActions() {
        return actions;
    }

    /*
     * return all actions required
     */
    public List<String> getActionsRequired() {
        ArrayList<String> names = new ArrayList();
        for (Action n : this.actions)
            if ("required".equals(n.getType()))
                names.add(n.getName());

        return names;
    }

    public List<String> getActionsNames() {
        ArrayList<String> names = new ArrayList();
        for (Action n : this.actions)
            names.add(n.getName() + "=>" + n.getType());

        return names;
    }

    @Override
    public String toString() {
        return "Form { " + "actions = " + this.getActionsNames() + ", submit = " + submit + ", type = " + type + ", validations = " + getValidations() + '}';
    }

    public boolean hasValidations() {
        return !this.validations.isEmpty();
    }

    /**
     * @param actions the actions to set
     */
    public void setActions(List<Action> actions) {
        this.actions = new ArrayList();
        for (Action a : actions){
            this.actions.add(new Action(a));
        }
    }

    /**
     * @return the submit
     */
    public String getSubmit() {
        return submit;
    }

    /**
     * @param submit the submit to set
     */
    public void setSubmit(String submit) {
        this.submit = submit;
    }

    /**
     * @return the condition
     */
    public String getCondition() {
        return condition;
    }

    /**
     * @param condition the condition to set
     */
    public void setCondition(String condition) {
        this.condition = condition;
    }

    /**
     * @return the error
     */
    public ErrorVertex getError() {
        return error;
    }

    /**
     * @param error the error to set
     */
    public void setError(ErrorVertex error) {
        this.error = error;
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

    /**
     * @return the type of form
     */
    public String getType() {
        return this.type;
    }

}
