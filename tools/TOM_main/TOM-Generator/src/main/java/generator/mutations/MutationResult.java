package generator.mutations;

import parsers.model.files.graph.elements.Action;
import java.util.List;

/**
 * @author raphael
 */
public class MutationResult {

    private List<Action> mutatedActions;
    private int testCaseMutated;
    private String mutationMessage;

    public MutationResult() {
        mutationMessage = "";
    }

    public MutationResult(List<Action> formActions) {
        mutatedActions = formActions;
    }

    /**
     * @return the mutatedActions
     */
    public List<Action> getMutatedActions() {
        return mutatedActions;
    }

    /**
     * @param mutatedActions the mutatedActions to set
     */
    public void setMutatedActions(List<Action> mutatedActions) {
        this.mutatedActions = mutatedActions;
    }

    /**
     * @return the testCaseMutated
     */
    public int getTestCaseMutated() {
        return testCaseMutated;
    }

    /**
     * @param testCaseMutated the testCaseMutated to set
     */
    public void setTestCaseMutated(int testCaseMutated) {
        this.testCaseMutated = testCaseMutated;
    }

    /**
     * @return the mutationMessage
     */
    public String getMutationMessage() {
        return mutationMessage;
    }

    /**
     * @param mutationMessage the mutationMessage to set
     */
    public void setMutationMessage(String mutationMessage) {
        this.mutationMessage = mutationMessage;
    }


}
