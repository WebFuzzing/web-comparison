package generator.generators;

import generator.mutations.MutationResult;
import org.apache.commons.io.IOUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import parsers.config.files.Mutation;
import parsers.config.files.Value;
import parsers.model.files.graph.elements.*;
import utils.DefaultValues;
import utils.DefaultValues.typeOfMutations;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.PrintWriter;
import java.io.UnsupportedEncodingException;
import java.util.*;
import static utils.DefaultValues.typeOfMutations.*;

/**
 * @author Marcelo Gonçalves
 */
public class CodeGeneratorIRIT implements CodeGenerator {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    private static final String CLOSESTEP = "\t\t</step>\n";

    private String model;

    private String pathFolder;

    // Contents of VALUES file
    private Map<String, Value> values;
    private Map<String, Mutation> mutationsValues;

    // Mutation to perform
    private Mutation mutation;

    // Flag for a test does not have more than one mutation
    private boolean alreadyMutated;

    // Contents of the normal test file
    private String normalTest;

    private MutationGeneratorIRIT mutationGenerator;

    // Contents of VALUES file
    private Map<String, List<String>> objectsByID;
    private Map<String, List<String>> stepsByID;
    private Map<String, Integer> stepPosition;
    private Map<String, Integer> modelPosition;
    private Map<String, String> stepsSlip;

    private boolean hasForm;
    private boolean slipCall;

    private int totalOfTests;
    private int totalOfTestsMutated;

    public CodeGeneratorIRIT(Map<String, Value> values, Map<String, Mutation> mutationsValues, String model, String pathFolder) {
        this.values = values;
        this.mutationsValues = mutationsValues;
        this.mutationGenerator = new MutationGeneratorIRIT();
        this.alreadyMutated = false;
        this.normalTest = "";
        this.model = model;
        this.pathFolder = pathFolder;
        this.totalOfTests = 0;
        this.totalOfTestsMutated = 0;
    }

    private List<Object> init(Path p, Mutation mutation) {
        List<Object> stepsOfPath = p.clonedSteps();
        hasForm = false;
        slipCall = false;

        objectsByID = new HashMap<>();
        stepsByID = new HashMap<>();
        stepPosition = new HashMap<>();
        modelPosition = new HashMap<>();
        stepsSlip = new HashMap<>();

        this.mutation = mutation;
        this.normalTest = "";
        this.alreadyMutated = false;

        return stepsOfPath;
    }

    private void generateEdgeCode(typeOfMutations typeOfMutation, ArrayList<String> steps, Edge edge){
        Event event = edge.getEvent();

        if (event instanceof Form) {
            Form f = (Form) event;
            hasForm = true;
            // Generate the code for the Form
            generateForm(f, typeOfMutation, steps);
        } else if (event instanceof Call) {
            Call l = (Call) event;
            // Generate the code for the Call
            generateCall(l, steps, typeOfMutation);
        }
    }

    private void checkSlip(ArrayList<String> steps, String origin, String target){
        int positionOrigin = modelPosition.get(origin);
        int positionTarget = modelPosition.get(target);

        if (stepPosition.containsValue(positionTarget)) {
            for (Map.Entry<String, Integer> entry : stepPosition.entrySet()) {
                if (positionTarget == entry.getValue()) {
                    stepPosition.put(entry.getKey(), positionOrigin);
                    break;
                }
            }
        }
        String originStep = steps.get(positionOrigin);
        String targetStep = steps.get(positionTarget);

        steps.set(positionOrigin, targetStep);
        steps.set(positionTarget, originStep);
    }

    private void slipCall(ArrayList<String> steps){
        // Do the slips
        for (Map.Entry<String, String> value : stepsSlip.entrySet()) {
            String origin = value.getKey();
            String target = value.getValue();

            if (!modelPosition.containsKey(target)) {
                return;
            }

            checkSlip(steps, origin, target);
        }
    }

    private boolean checkMutation(ArrayList<String> steps, typeOfMutations typeOfMutation){
        switch (typeOfMutation) {
            case LAPSE_CALL:
                int size = steps.size();
                int rnd = new Random().nextInt(size);
                steps.remove(rnd);
                break;
            case SLIP_CALL:
                mutationGenerator.slipCall(steps);
                break;
            case LAPSE_FORM:
                if (!hasForm) {
                    return false;
                }
                break;
            case MUTATIONS_FROM_FILE:
                if (slipCall) {
                    slipCall(steps);
                } else if (!alreadyMutated) {
                    return false;
                }
                break;
            default:
                break;
        }
        return true;
    }

    /**
     * Go through each step in the path
     * for each path there will be one scenario file
     *
     * @param p
     * @param typeOfMutation
     * @param filename
     */
    public void generate(Path p, typeOfMutations typeOfMutation, String filename, Mutation mutation) {

        List<Object> stepsOfPath = init(p, mutation);
        ArrayList<String> steps = new ArrayList<>();

        for (Object obj : stepsOfPath) {
            // Check the type of the Object
            switch (obj.getClass().getSimpleName()) {
                case "Vertex":
                    break;
                case "Edge":
                    generateEdgeCode(typeOfMutation, steps, (Edge) obj);
                    break;
                default:
            }
        }

        boolean flag = checkMutation(steps, typeOfMutation);

        if (flag) {
            generateTest(filename, steps, typeOfMutation);
        }

    }

    /**
     * Generate code for one call(e.g link,clicks)
     *
     * @param link
     */
    private void generateCall(Call link, List<String> steps, typeOfMutations typeOfMutation) {

        if (typeOfMutation == MUTATIONS_FROM_FILE && !this.alreadyMutated) {
            this.alreadyMutated = mutationGenerator.mutationFromFile(link.getModelName(), mutation, stepsSlip);

            if (this.alreadyMutated) {
                switch (mutation.getTypeOfMutation()) {
                    case "lapse_call":
                        // Don't add the step
                        return;
                    case "slip_call":
                        this.slipCall = true;
                        break;
                    default:
                        break;
                }
            }
        }

        String middle = buildStep(link.getModelName()).concat(CLOSESTEP);

        modelPosition.put(link.getModelName(), steps.size());
        steps.add(middle);
    }

    /**
     * Generate de code for the forms 1- Get Actions in Form(
     * ex: field textfield) 2- Check for conditions 3- Submit
     * Form
     *
     * @param form
     * @param typeOfMutation
     */
    private typeOfMutations generateForm(Form form, typeOfMutations typeOfMutation, ArrayList<String> steps) {

        List<Action> mutatedActions;

        /**
         * CHECK For all the Mutations
         */

        MutationResult mutationResult = mutationGenerator.checkFormMutations(typeOfMutation, form, this.alreadyMutated);

        mutatedActions = mutationResult.getMutatedActions();

        String middle = buildStep(form.getModelName());

        if (typeOfMutation == LAPSE_FORM && mutatedActions.isEmpty()) {

            middle = middle.concat(CLOSESTEP);

            steps.add(middle);

        } else {

            if (typeOfMutation == MUTATIONS_FROM_FILE && !this.alreadyMutated) {

                this.alreadyMutated = mutationGenerator.mutationFromFile(form.getModelName(), mutation, stepsSlip);

                typeOfMutations res = checkMutationFromFile(typeOfMutation, middle, steps, form);

                if (res != null) {
                    return res;
                }

            }

            typeOfMutations res = generateActions(steps, mutatedActions, form, middle, typeOfMutation);

            if (res != null) {
                return res;
            }
        }

        if (!this.alreadyMutated) {
            String flag = mutationResult.getMutationMessage();
            this.alreadyMutated = "True".equals(flag);
        }

        return typeOfMutation;
    }

    private void buildString(List<String> value, List<String> listValues, List<String> listSteps, String middle, Action a){
        for (String s : value) {
            String type;

            try {
                Integer.parseInt(s);
                type = "int";
            } catch (NumberFormatException e) {
                type = "String";
            }

            String object = "\t\t<object objectClass=\"" + type + "\" objectContent=\"" + s + "\" objectID=\"" + a.getName() + "\"/>\n";

            String step = middle;
            step = step.concat("\t\t\t\t<stepObject objectID=\"" + a.getName() + "\"/>\n");
            step = step.concat("\t\t\t</task>\n\t\t</step>\n");
            listSteps.add(step);
            listValues.add(object);
        }
    }

    private typeOfMutations generateActions(ArrayList<String> steps, List<Action> mutatedActions, Form form, String middle, typeOfMutations typeOfMutation){
        // Generate actions
        for (Action a : mutatedActions) {

            if (values.get(a.getName()) == null) {
                middle = middle.concat(CLOSESTEP);
                modelPosition.put(form.getModelName(), steps.size());
                steps.add(middle);
                return typeOfMutation;
            } else {
                List<String> value = values.get(a.getName()).getValues();
                List<String> listValues = new ArrayList<>();
                List<String> listSteps = new ArrayList<>();

                buildString(value, listValues, listSteps, middle, a);

                objectsByID.put(a.getName(), listValues);
                stepsByID.put(a.getName(), listSteps);
                stepPosition.put(a.getName(), steps.size());

                modelPosition.put(form.getModelName(), steps.size());

                steps.add("");
            }
        }
        return null;
    }

    private typeOfMutations checkMutationFromFile(typeOfMutations typeOfMutation, String middle, ArrayList<String> steps, Form form){
        if (this.alreadyMutated) {
            String mutationType = this.mutation.getTypeOfMutation();

            switch (mutationType) {
                case "lapse_call":
                    // Don't add the step
                    return typeOfMutation;
                case "lapse_form":
                    middle = middle.concat(CLOSESTEP);
                    modelPosition.put(form.getModelName(), steps.size());
                    steps.add(middle);
                    return typeOfMutation;
                case "slip_call":
                    slipCall = true;
                    break;
                default:
                    break;
            }
        }
        return null;
    }

    /**
     * @return the VALUES
     */
    public Map<String, Value> getValues() {
        return values;
    }

    /**
     * @param values the VALUES to set
     */
    public void setValues(Map<String, Value> values) {
        this.values = values;
    }

    /**
     * Build String step
     *
     * @param taskid
     */
    private String buildStep(String taskid) {
        return "\t\t<step referencemodel=\"" + model + "\" " +
                "role=\"pilot\" taskdate=\"" + "00" + "\" taskdatelong=\"" + "00" + "\">\n" +
                "\t\t\t<task taskid=\"" + taskid + "\">\n";
    }

    /**
     * Generate test case content
     *
     * @param filename
     * @param steps
     * @param typeOfMutation
     */
    private void generateTest(String filename, ArrayList<String> steps, typeOfMutations typeOfMutation) {
        String test;

        int size = 1;
        boolean flag = false;

        if (!objectsByID.isEmpty()) {
            String key = objectsByID.keySet().iterator().next();
            size = objectsByID.get(key).size();
            flag = true;
        }
        String file;
        for (int i = 0; i < size; i++) {

            StringBuilder sb = new StringBuilder();

            sb.append("<hamsterscenario date=\"0\" " +
                    "simulatedmodel=\"" + model + "\" version=\"0\">\n" +
                    "\n\t<objects>\n");

            ArrayList<String> st = (ArrayList<String>) steps.clone();

            if (flag) {
                for (Map.Entry<String, List<String>> obj : objectsByID.entrySet()) {
                    String key = obj.getKey();
                    sb.append(obj.getValue().get(i));

                    if (typeOfMutation == LAPSE_CALL && stepPosition.get(key) >= st.size()) {
                        // Do nothing Step removed
                        int x = 0;
                    } else {
                        st.set(stepPosition.get(key), stepsByID.get(key).get(i));
                    }
                }
                file = filename + "-" + (i + 1) + ".scen";
            } else {
                file = filename + ".scen";
            }

            sb.append("\t</objects>\n\n\t<steps>\n");

            for (String s : st) {
                sb.append(s);
            }

            sb.append("\t</steps>\n" + "</hamsterscenario>");
            test = sb.toString();

            if (typeOfMutation == NORMAL && "".equals(normalTest)) {
                normalTest = test;
            } else if (test.equals(normalTest)) {
                return;
            }

            if(typeOfMutation != NORMAL){
                this.totalOfTestsMutated++;
            }

            writeFile(file, test);

        }
    }

    private void generateTests(List<DefaultValues.typeOfMutations> testTypes, Path p, int number) {
        boolean flag = false;
        // Types of possible tests_generators.MUTATIONS
        String dir = pathFolder + "IRITScenarios/";

        // Foreach type of test create a scenario from the path
        for (DefaultValues.typeOfMutations i : testTypes) {
            if (i == DefaultValues.typeOfMutations.MUTATIONS_FROM_FILE) {

                if (mutationsValues == null || mutationsValues.isEmpty()) {
                    flag = true;
                    logger.error("Missing mutations values! Please check the configuration file.");
                    continue;
                }

                for (Mutation mutationAux : mutationsValues.values()) {
                    String type = mutationAux.getTypeOfMutation();
                    String file = dir + DefaultValues.typeOfMutations.MUTATIONS_FROM_FILE.toString() + "/" + type + "/Id_" + mutationAux.getId() + "/Scenario_N" + number;
                    // Go through each step of the path
                    generate(p, i, file, mutationAux);
                }
            } else {
                String file = dir + i.toString() + "/Scenario_N" + number;
                // Go through each step of the path
                generate(p, i, file, null);
            }
        }
        if (flag) {
            testTypes.remove(DefaultValues.typeOfMutations.MUTATIONS_FROM_FILE);
        }
    }

    @Override
    public void testsGeneration(List<Path> paths, List<DefaultValues.typeOfMutations> testTypes) {
        int number = 0;

        testTypes.remove(typeOfMutations.MISTAKE_MUTATION);
        testTypes.remove(typeOfMutations.DOUBLE_CLICK_MUTATION);
        testTypes.remove(typeOfMutations.REMOVE_REQUIRED_FIELD);
        testTypes.remove(typeOfMutations.DOUBLE_CLICK_CALL);
        testTypes.remove(typeOfMutations.DOUBLE_CLICK_MENU_CALL);
        testTypes.remove(typeOfMutations.INJECT_BACK_EVENT);
        testTypes.remove(typeOfMutations.INJECT_REFRESH_EVENT);

        for (Path p : paths) {
            generateTests(testTypes, p, number);
            number++;
        }
    }

    /**
     * Write the test into a file
     *
     * @param filename
     * @param test     - content for the file
     */
    private void writeFile(String filename, String test) {
        File file = new File(filename);

        file.getParentFile().mkdirs();

        PrintWriter writer = null;
        try {
            writer = new PrintWriter(file, "UTF-8");
            writer.println(test);
            this.totalOfTests++;
        } catch (FileNotFoundException | UnsupportedEncodingException e) {
            logger.error("Error in test file generation: " + e.getMessage());
        } finally {
            IOUtils.closeQuietly(writer);
        }
    }

    @Override
    public int getNumberOfTestsGenerated(){
        return this.totalOfTests;
    }

    @Override
    public int getNumberOfTestsMutated(){
        return this.totalOfTestsMutated;
    }
}
