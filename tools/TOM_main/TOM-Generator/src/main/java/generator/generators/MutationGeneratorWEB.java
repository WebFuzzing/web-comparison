package generator.generators;

import com.sun.codemodel.JBlock;
import com.sun.codemodel.JCodeModel;
import generator.mutations.*;
import parsers.config.files.Mapping;
import parsers.config.files.Mutation;
import parsers.config.files.Value;
import parsers.model.files.graph.elements.Action;
import parsers.model.files.graph.elements.Call;
import parsers.model.files.graph.elements.Form;
import parsers.model.files.graph.elements.Interaction;
import utils.DefaultValues;
import utils.JCodeUtil;
import utils.RandomUtil;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

/**
 * @author raphael
 */
public class MutationGeneratorWEB {

    private IFormMutation formMutations;

    private ICallMutation callMutations;

    /**
     * Do a mistake mutation in a value
     *
     * @param form
     * @param v
     * @param formBlock
     * @return
     */
    public MutationResult mistakeMutation(Form form, Value v, JBlock formBlock) {

        formMutations = new FormMutation(form.getActions());
        MutationResult mutationResult = new MutationResult();

        v.setValue(formMutations.mistake(v.getValues().get(0), 10, 1));
        report(mutationResult, formBlock, "Mistake");

        return mutationResult;
    }

    /**
     * Do a random mistake mutation in a list of actions
     *
     * @param formActions
     * @param values
     * @param form
     * @param formBlock
     * @return
     */
    public MutationResult mistakeMutationRandom(List<Action> formActions, Map<String, Mapping> mapping, Map<String, Value> values, Form form, JBlock formBlock, int testCaseMutated, boolean doMutation) {

        formMutations = new FormMutation(form.getActions());
        MutationResult mutationResult = new MutationResult(formActions);

        if (doMutation) {
            int size = 0;
            ArrayList<Integer> validPositions = new ArrayList<>();

            for (Action a : formActions) {
                Mapping m = mapping.get(a.getName());

                if ("sendKeys".equals(m.getWhatToDo())) {
                    validPositions.add(size);
                }
                size++;
            }

            int random = RandomUtil.randomNumber(0, validPositions.size() - 1);
            Action actionWithMistake = formActions.get(validPositions.get(random));
            Value v = values.get(actionWithMistake.getName());
            v.setValue(formMutations.mistake(v.getValues().get(0), 10, 1));
            report(mutationResult, formBlock, "Mistake");
        } else {
             mutationResult.setMutationMessage("");
             mutationResult.setTestCaseMutated(testCaseMutated);
        }

        return mutationResult;
    }

    public MutationResult doubleClickSubmitMutation(JCodeModel cm, Mapping map, JBlock jb, int varNumber) {
        MutationResult mutationResult = new MutationResult();

        // Generate a double click
        JCodeUtil.report(jb, "Mutation doubleClickSubmitMutation");
        JCodeUtil.doubleClickAction(cm, jb, map, varNumber);

        mutationResult.setMutationMessage("Reporter.log(\"Mutation DoubleClickSubmitMutation killed <br>\");");
        mutationResult.setTestCaseMutated(1);
        return mutationResult;
    }

    /**
     * check for tests_generators.MUTATIONS in a form
     *
     * @param testTypes
     * @param form
     * @param formBlock
     * @return
     */
    public MutationResult checkFormMutations(DefaultValues.typeOfMutations testTypes, Form form, JBlock formBlock, int testCaseMutated, boolean doMutation) {
        // Check for the type of mutation to do
        MutationResult mutationResult = new MutationResult();
        formMutations = new FormMutation(form.getActions());
        DefaultValues.typeOfMutations type = DefaultValues.typeOfMutations.NORMAL;

        if (doMutation) { type = testTypes; }

        switch (type) {
            case SLIP_CALL:
                mutationResult.setMutatedActions(formMutations.slip());
                report(mutationResult, formBlock, "Slip");
                break;
            case LAPSE_FORM:
                mutationResult.setMutatedActions(formMutations.lapse());
                report(mutationResult, formBlock, "Lapse");
                break;
            case REMOVE_REQUIRED_FIELD:
                mutationResult.setMutatedActions(formMutations.removeRequiredInput());
                report(mutationResult, formBlock, "Remove Required Field");
                break;
            default:
                mutationResult.setMutatedActions(formMutations.actions());
                mutationResult.setMutationMessage("");
                mutationResult.setTestCaseMutated(testCaseMutated);
        }

        return mutationResult;
    }

    /**
     * Do a double click Mutation in a Call
     *
     * @param link
     * @param jb
     */
    public void doubleClickCallMutation(Call link, JBlock jb) {
        CallMutation callMutation = new CallMutation();
        callMutation.doubleClick(link);
        JCodeUtil.report(jb, "[Mutation]: Double Click in " + link.getModelName());
    }

    /**
     * Do a double click Mutation in a Menu
     *
     * @param callMenus
     * @param jb
     */
    public void doubleClickMenuCallMutation(List<Interaction> callMenus, JBlock jb) {
        CallMutation callMutation = new CallMutation();
        callMutation.randomDoubleClickMenu(callMenus);
        JCodeUtil.report(jb, "[Mutation]: Double Click in a Menu");
    }

    /**
     * Inject a mutation in a JBlock
     *
     * @param jb
     * @param testType
     */
    public boolean checkForInjectEvents(JBlock jb, DefaultValues.typeOfMutations testType) {
        switch (testType) {
            case INJECT_BACK_EVENT:
                injectEventBackEvent(jb);
                break;
            case INJECT_REFRESH_EVENT:
                injectEventRefreshEvent(jb);
                break;
            default:
                return false;
        }
        return true;
    }


    public boolean callMutationsFromFile(Call call, Map<String, Mutation> mutations, JBlock jb) {
        // Verify if we need to do a mutation
        if (mutations.containsKey(call.getId())) {
            Mutation mutation = mutations.get(call.getId());
            String typeOfMutation = mutation.getTypeOfMutation();
            callMutations = new CallMutation();

            // Do the switch here and change the form_action
            switch (typeOfMutation) {
                case "doubleClick":
                    callMutations.doubleClick(call);
                    JCodeUtil.report(jb, "[Mutation]: Double Click in " + call.getId());
                    return true;
                case "doubleClickMenu":
                    callMutations.randomDoubleClickMenu(call.getSteps());
                    JCodeUtil.report(jb, "[Mutation]: Double Click Menu " + call.getId());
                    return true;
                default:
                    break;
            }
        }
        return false;
    }

    public MutationResult formMutationFromFile(List<Action> formActions, Map<String, Value> values,
                                               Map<String, Mutation> mutations, JBlock formBlock) {

        MutationResult mutationResult = new MutationResult();
        mutationResult.setMutatedActions(formActions);

        formMutations = new FormMutation(formActions);
        Value value;

        for (Action action : formActions) {
            Mutation mutation = mutations.get(action.getName());

            // If not null it means that we need to do a mutation
            if (mutation != null) {
                String typeOfMutation = mutation.getTypeOfMutation();

                // Do the switch here and change the form_action
                switch (typeOfMutation) {
                    case "lapse":
                        // Remove the Model
                        mutationResult.setMutatedActions(formMutations.lapseAction(action));
                        report(mutationResult, formBlock, "Lapse From File in " + action.getName());
                        setFailTypeOfTest(mutation, mutationResult);
                        break;
                    case "slip":
                        // Change the position with order random
                        mutationResult.setMutatedActions(formMutations.slipAction(action));
                        report(mutationResult, formBlock, "Slip From File in " + action.getName());
                        setFailTypeOfTest(mutation, mutationResult);
                        break;
                    case "mistake":
                        // Get the Values of the element name
                        value = values.get(mutation.getModelElement());
                        value.setValue(mutation.getValue());
                        report(mutationResult, formBlock, "Mistake From File in " + action.getName());
                        setFailTypeOfTest(mutation, mutationResult);
                        break;
                    default:
                        break;
                }
                // Change the form mutation actions
                formMutations.setFormActions(mutationResult.getMutatedActions());
            }
        }
        return mutationResult;
    }

    private void setFailTypeOfTest(Mutation mutation, MutationResult mutationResult) {
        mutationResult.setTestCaseMutated(mutation.getGonnaFail());
    }

    private void report(MutationResult mutationResult, JBlock jb, String message) {
        jb.directStatement("Reporter.log(\" Mutation " + message + " <br>\");");
        String mutationMessage = "Reporter.log(\" Mutation " + message + " killed <br>\");";
        mutationResult.setMutationMessage(mutationMessage);
        mutationResult.setTestCaseMutated(1);
    }

    private void injectEventBackEvent(JBlock jb) {
        JCodeUtil.comment(jb, "Injected Back Event ");
        jb.directStatement("driver.navigate().back();");
        jb.directStatement("Reporter.log(\"[Mutation] Injected Event Go Back\");");
    }

    private void injectEventRefreshEvent(JBlock jb) {
        JCodeUtil.comment(jb, "Injected Refresh Event ");
        jb.directStatement("driver.navigate().refresh();");
        jb.directStatement("Reporter.log(\"[Mutation] Injected Event Go Refresh\");");
    }

}
