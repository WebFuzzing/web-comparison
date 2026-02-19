package parsers.config.files;

/**
 * @author raphael
 */
public class Mutation {

    private String id;

    // Type of the mutation we want to do
    private String typeOfMutation;

    // Name of the element in the MODEL
    private String modelElement;

    // Value of the element
    private String value;

    private int gonnaFail;

    public Mutation() {
        typeOfMutation = "";
        value = "";
        modelElement = "";
    }

    public Mutation(String name, String s) {
        this.modelElement = name;
        this.value = s;
        this.gonnaFail = 0;
    }

    public Mutation(String typeOfMutation, String name, String value) {
        this.typeOfMutation = typeOfMutation;
        this.modelElement = name;
        this.value = value;
        this.gonnaFail = 0;
    }

    public Mutation(String typeOfMutation, String name, int gonnaFail) {
        this.typeOfMutation = typeOfMutation;
        this.modelElement = name;
        this.gonnaFail = gonnaFail;
    }

    /**
     * @return the value
     */
    public String getValue() {
        return value;
    }

    /**
     * @param value the value to set
     */
    public void setValue(String value) {
        this.value = value;
    }

    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();
        sb.append("{\n");
        sb.append("\t\"type=\": \"" + getTypeOfMutation() + "\",\n");

        if ("".equals(getModelElement())) {
            sb.append("\t\"model_element=\": \"" + getModelElement() + "\",\n");
        } else {
            sb.append("\t\"target=\": \"" + getModelElement() + "\",\n");
        }

        sb.append("\t\"fail=\": \"" + getGonnaFail() + "\"\n");
        sb.append("}");
        return sb.toString();
    }

    /**
     * @return the gonnaFail
     */
    public int getGonnaFail() {
        return gonnaFail;
    }

    /**
     * @param gonnaFail the gonnaFail to set
     */
    public void setGonnaFail(int gonnaFail) {
        this.gonnaFail = gonnaFail;
    }

    /**
     * @return the typeOfMutation
     */
    public String getTypeOfMutation() {
        return typeOfMutation;
    }

    /**
     * @param typeOfMutation the typeOfMutation to set
     */
    public void setTypeOfMutation(String typeOfMutation) {
        this.typeOfMutation = typeOfMutation;
    }

    /**
     * @return the modelElement
     */
    public String getModelElement() {
        return modelElement;
    }

    /**
     * @param modelElement the modelElement to set
     */
    public void setModelElement(String modelElement) {
        this.modelElement = modelElement;
    }

    /**
     * @return the modelElement
     */
    public String getId() {
        return id;
    }

    /**
     * @param id the modelElement to set
     */
    public void setId(String id) {
        this.id = id;
    }
}
