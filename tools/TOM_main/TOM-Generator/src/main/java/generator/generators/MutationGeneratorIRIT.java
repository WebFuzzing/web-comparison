package generator.generators;

import generator.mutations.FormMutation;
import generator.mutations.MutationResult;
import parsers.config.files.Mutation;
import parsers.model.files.graph.elements.Action;
import parsers.model.files.graph.elements.Form;
import generator.mutations.IFormMutation;
import utils.DefaultValues;
import java.util.List;
import java.util.Map;
import java.util.Random;
import static utils.DefaultValues.typeOfMutations.LAPSE_FORM;
import static utils.DefaultValues.typeOfMutations.NORMAL;

/**
 * @author Marcelo Gonçalves
 */
public class MutationGeneratorIRIT {

    private IFormMutation formMutations;

    /**
     * check for tests_generators.MUTATIONS in a form
     *
     * @param typeOfMutation
     * @param form
     * @return
     */
    public MutationResult checkFormMutations(DefaultValues.typeOfMutations typeOfMutation, Form form, boolean flag) {
        MutationResult mutationResult = new MutationResult();
        formMutations = new FormMutation(form.getActions());

        DefaultValues.typeOfMutations opt = typeOfMutation;

        if (flag) {
            opt = NORMAL;
        }

        //Check for the type of type_of_mutation to do

        if(opt == LAPSE_FORM){
            Action a = form.getActions().get(0);
            mutationResult.setMutatedActions(formMutations.lapseAction(a));
            mutationResult.setMutationMessage("True");
        } else {
            mutationResult.setMutatedActions(formMutations.actions());
            mutationResult.setMutationMessage("False");
            mutationResult.setTestCaseMutated(0);
        }

        return mutationResult;
    }

    public boolean mutationFromFile(String link, Mutation mutation, Map<String, String> stepsSlip) {

        if (mutation.getModelElement().equals(link)) {
            String mutationType = mutation.getTypeOfMutation();

            //do the switch here and change the form_action
            switch (mutationType) {
                case "lapse_call":
                case "lapse_form":
                    return true;
                case "slip_call":
                    // add the data to the auxiliary maps
                    stepsSlip.put(link, mutation.getValue());
                    return true;
                default:
                    break;
            }
        }
        return false;
    }

    public void slipCall(List<String> steps) {
        int size = steps.size();

        if(size == 1){
            return;
        }

        int rnd = new Random().nextInt(size);

        String value;
        String aux;
        int i;
        int z;

        if (rnd == (size - 1)) {
            i = rnd - 1;
            z = i - 1;
        } else if (rnd == 0) {
            i = rnd + 1;
            z = i + 1;
        } else {
            i = rnd + 1;
            z = rnd - 1;
        }

        value = steps.get(rnd);
        aux = steps.get(i);

        steps.set(i, value);
        value = steps.get(z);
        steps.set(z, aux);
        steps.set(rnd, value);
    }

}
