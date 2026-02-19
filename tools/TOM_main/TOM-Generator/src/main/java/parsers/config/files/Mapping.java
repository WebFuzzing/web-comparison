package parsers.config.files;

/**
 * @author raphaelrodrigues
 */
public class Mapping {

    private String howToFind;
    private String whatToFind;
    private String whatToDo;
    private String typeOfAction;
    private String wait;

    public Mapping() {
        // We don't need to initialize the variables they are all strings
    }

    public Mapping(Mapping map) {
        this.howToFind = map.getHowToFind();
        this.whatToDo = map.getWhatToDo();
        this.whatToFind = map.getWhatToFind();
        this.typeOfAction = map.getTypeOfAction();
        this.wait = map.getWait();
    }

    /**
     * @return the howToFind
     */
    public String getHowToFind() {
        return howToFind;
    }

    /**
     * @return the typeOfAction
     */
    public String getTypeOfAction() {
        return typeOfAction;
    }

    /**
     * @return the wait
     */
    public String getWait() {
        return wait;
    }

    /**
     * @return the whatToDo
     */
    public String getWhatToDo() {
        return whatToDo;
    }

    /**
     * @return the whatToFind
     */
    public String getWhatToFind() {
        return whatToFind;
    }

    /**
     * @param howToFind the howToFind to set
     */
    public void setHowToFind(String howToFind) {
        this.howToFind = howToFind;
    }

    /**
     * @param typeOfAction the typeOfAction to set
     */
    public void setTypeOfAction(String typeOfAction) {
        this.typeOfAction = typeOfAction;
    }

    /**
     * @param wait the wait to set
     */
    public void setWait(String wait) {
        this.wait = wait;
    }

    /**
     * @param whatToDo the whatToDo to set
     */
    public void setWhatToDo(String whatToDo) {
        this.whatToDo = whatToDo;
    }

    /**
     * @param whatToFind the whatToFind to set
     */
    public void setWhatToFind(String whatToFind) {
        this.whatToFind = whatToFind;
    }

    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();
        sb.append("{\n");
        sb.append("\t\"howToFind=\": \"" + howToFind + "\",\n");
        sb.append("\t\"whatToFind=\": \"" + whatToFind + "\",\n");
        sb.append("\t\"whatToDo=\": \"" + whatToDo + "\",\n");
        sb.append("\t\"typeOfAction=\": \"" + typeOfAction + "\",\n");
        sb.append("\t\"wait=\": \"" + wait + "\",\n");
        sb.append("}");
        return sb.toString();
    }

}
