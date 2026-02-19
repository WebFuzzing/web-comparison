package generator.generators;

import com.sun.codemodel.*;
import generator.mutations.MutationResult;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.testng.annotations.Test;
import parsers.config.files.Mapping;
import parsers.config.files.Mutation;
import parsers.config.files.Value;
import parsers.model.files.graph.elements.*;
import utils.DefaultValues;
import utils.JCodeUtil;
import utils.RandomUtil;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import static utils.DefaultValues.typeOfMutations.*;

public class TestGeneratorWEB {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    // Mapping Values from the Model to the GUI
    private Map<String, Mapping> mapping;

    // Input Values
    private Map<String, Value> values;

    // Chosen Mutations
    private Map<String, Mutation> mutations;

    private MutationGeneratorWEB mutationGenerator;

    private int testCaseMutated;
    private int mutationFromFile;

    private Settings settings;

    // Number of the test
    private int varNumber;

    public TestGeneratorWEB(Map<String, Value> values, Map<String, Mutation> mutations,
                            Map<String, Mapping> mapping, Settings settings) {
        this.mapping = mapping;
        this.values = values;
        this.mutations = mutations;
        this.settings = settings;
        this.mutationGenerator = new MutationGeneratorWEB();
    }

    /**
     * Go go through each step in the path For each Step in
     * the path, will be created a Test Case For each path
     * there will be one group of tests.
     *
     * @param jCodeModel
     * @param dc
     * @param p
     * @param methodBrokenLinks
     * @param methodBrokenImages
     * @param wait
     * @param testType
     * @param pathNum
     */
    public int generateTest(JCodeModel jCodeModel, JDefinedClass dc, Path p, JMethod methodBrokenLinks,
                             JMethod methodBrokenImages, JMethod wait, DefaultValues.typeOfMutations testType, int pathNum) {

        this.testCaseMutated = 0;
        this.mutationFromFile = 0;

        List<Object> stepsOfPath = p.clonedSteps();

        // Go go through each step in this Path
        this.varNumber = 1;

        // Init the block of content of this method
        JMethod method = initMethod(jCodeModel, dc, pathNum, p.toString());

        // Initialize the Block of the method
        JBlock jb = method.body();

        // Check if we want to check for broken images
        if (this.settings.getCheckForBrokenImages() == 1) {
            // Invoke the method broken_images
            jb.invoke(methodBrokenImages);
        }
        if (this.settings.getCheckForBrokenLinks() == 1) {
            // Invoke the method broken_links
            jb.invoke(methodBrokenLinks);
        }

        int formCount = 0;
        int mutationPosition = 0;

        if (testType != NORMAL) {
            mutationPosition = checkFormMutations(testType, stepsOfPath);
        }

        // Foreach path generate a case test
        for (Object obj : stepsOfPath) {
            boolean flag;

            // Check the type of Object
            switch (obj.getClass().getSimpleName()) {
                case "Vertex":
                    Vertex v = (Vertex) obj;
                    flag = this.generateVertex(v, jb, jCodeModel, wait);
                    checkForPreviousMutation(jb, this.testCaseMutated);
                    break;
                case "Edge":
                    Edge edge = (Edge) obj;
                    Event event = edge.getEvent();

                    if (event instanceof Form) {
                        Form f = (Form) event;

                        // Generate the code for the Form
                        this.generateForm(f, jb, testType, jCodeModel, wait, mutationPosition == formCount);
                        checkForPreviousMutation(jb, this.testCaseMutated);
                        formCount++;

                        // Log system
                        JCodeUtil.report(jb, "[Pass]: Form Completed ID:" + f.getId());
                    } else if (event instanceof Call) {
                        Call l = (Call) event;

                        // Generate the code for the Call
                        this.generateCall(jCodeModel, l, jb, testType);
                        checkForPreviousMutation(jb, this.testCaseMutated);

                        // Log system
                        JCodeUtil.report(jb, "[Pass]: Call Completed ID:" + l.getId());
                    }
                    flag = true;

                    break;
                default:
                    flag = false;
                    jb.directStatement("System.out.println(\"" + obj.getClass().toString() + "\");");
            }

            // After each step of the path check if we want to inject an event
            if (mutationGenerator.checkForInjectEvents(jb, testType) || this.mutationFromFile == 1){
                this.testCaseMutated = 1;
            }

            // Invoke the wait method in code
            if (flag) {
                jb.invoke(wait);
            }
        }

        if (testType != NORMAL && this.testCaseMutated == 0) {
            dc.methods().remove(method);
            return 0;
        }

        return 1;
    }

    /**
     * @param dc
     * @param pathNum
     * @return
     */
    private JMethod initMethod(JCodeModel jCodeModel, JDefinedClass dc, int pathNum, String pathToString) {

        // Create method name for this path
        JMethod m = dc.method(1, void.class, "test" + pathNum);
        JBlock jb = m.body();

        // Put the annotations in the method
        m.annotate(Test.class).param("invocationCount", 1).param("groups", Integer.toString(pathNum));

        // Throw the exceptions
        m._throws(InterruptedException.class);
        m._throws(IOException.class);

        // Adding class level comment
        JDocComment jDocComment = m.javadoc();

        // Path Info
        jDocComment.add("Path number: " + pathNum);
        jDocComment.add("Path: " + pathToString);

        // Create a variable and initialize with 0
        jb.decl(jCodeModel.ref(Integer.class).unboxify(), "gonnaFail", JExpr.lit(0));

        return m;
    }

    /**
     * Generate code for one call (e.g link,clicks)
     *
     * @param link
     * @param jb
     */
    private void generateCall(JCodeModel jCodeModel, Call link, JBlock jb, DefaultValues.typeOfMutations testType) {
        Call aux = new Call(link);
        JCodeUtil.comment(jb, "click " + link.getId());
        String modelName;
        JBlock blockCall = new JBlock(false, false);
        Mapping map;

        if (testType == MUTATIONS_FROM_FILE) {
            // Search for call tests_generators.MUTATIONS in file
            if (mutationGenerator.callMutationsFromFile(aux, mutations, blockCall)) {
                this.mutationFromFile = 1;
            }
        }

        modelName = aux.getModelName();
        map = this.mapping.get(modelName);

        // Link can have steps (for a menu for example)
        if (aux.hasSteps()) {

            // Check for mutations
            if (testType == DOUBLE_CLICK_MENU_CALL) {
                mutationGenerator.doubleClickMenuCallMutation(aux.getSteps(), jb);
                this.testCaseMutated = 1;
            }

            // Generate the steps or calls in the menu
            generateMenuCalls(jCodeModel, aux.getSteps(), jb);
            JCodeUtil.clickAction(jb, map);
            // jb.invoke(wait);
        } else {

            // Check for Mutations in the Call
            if (testType == DOUBLE_CLICK_CALL) {
                mutationGenerator.doubleClickCallMutation(aux, blockCall);
                this.testCaseMutated = 1;
            }

            // Check if the link is a click or double click
            if ("click".equals(aux.getTypeOfCall())) {
                JCodeUtil.clickAction(blockCall, map);
            } else {
                JCodeUtil.doubleClickAction(jCodeModel, blockCall, map, varNumber++);
            }

            // Generate a block with a try an catch
            JCodeUtil.tryCBWithMsgExcept(jb, jCodeModel, blockCall, "[FAIL]: Error in Call " + aux.getModelName());
        }
    }

    /**
     * Generate code for actions in Menus
     *
     * @param steps
     * @param jb
     */
    private void generateMenuCalls(JCodeModel jCodeModel, List<Interaction> steps, JBlock jb) {
        Mapping map;
        for (Interaction s : steps) {
            map = this.mapping.get(s.getName());

            if("action".equals(map.getWhatToDo())){
                // Generate a click on a menu
                JBlock actionBlock = JCodeUtil.clickOnMenu(map);
                JCodeUtil.report(actionBlock, "[Pass]: Click in a Menu " + s.getName());
                JCodeUtil.tryCBWithMsgExcept(jb, jCodeModel, actionBlock, "Error in Menu Click " + s.getName());
            } else {
                String str = "driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\"))." + map.getWhatToDo() + "();";
                JCodeUtil.tryCBWithMsgExcept1(jb, jCodeModel, str, "[FAIL]: Error in Menu Click", this.settings.getScreenshotOnErrors(), logForPreviousMutation(), this.settings.getScreenShotsPath());
            }
        }
    }

    /**
     * Generate code for a state(e.g web page)
     *
     * @param v
     * @param jb
     * @param cm
     */
    private boolean generateVertex(Vertex v, JBlock jb, JCodeModel cm, JMethod wait) {

        boolean hasValidations = v.hasValidations();

        // Check if this state has validations
        if (!hasValidations) {
            return false;
        }

        // Comment
        JCodeUtil.comment(jb, "page " + v.getName());

        // Log system
        JCodeUtil.report(jb, " Enter in page " + v.getName());

        // Generate Asserts (validations)
        this.generateAsserts(v.getValidations().getValidationList(), jb, cm, wait);

        return true;
    }

    /**
     * Generate de code for the validations (asserts)
     *
     * @param validations
     * @param jb
     * @param cm
     */
    private void generateAsserts(List<Validation> validations, JBlock jb, JCodeModel cm, JMethod wait) {

        JCodeUtil.comment(jb, "Asserts");

        // Number of validations to do in the Vertex
        int numValidations = 0;
        int size = validations.size();

        for (Validation v : validations) {
            // Create a block with try and catch with the validation
            if (numValidations == 0) {

                List<String> stringCatch = new ArrayList<>();
                stringCatch.add("Reporter.log(\"[Fail]: In a Validation " + v.getId() + " \");");
                stringCatch.add("fail(\" [Fail]: In a Validation " + v.getId() + " [Message] => \" +  " + "_x.getMessage() " + ");");

                JCodeUtil.tryCatchBlock(jb, cm, typeAssert(v), stringCatch, this.settings.getScreenshotOnErrors(), logForPreviousMutation(), "AssertionError", this.settings.getScreenShotsPath());
            } else {
                // Add comment
                JCodeUtil.comment(jb, "Validation Test On entry in a Page");

                List<String> stringCatch = new ArrayList<>();
                stringCatch.add("Reporter.log(\"[Fail]: In a Validation " + v.getId() + " \");");
                stringCatch.add("fail(\" [Fail]: In a Validation [Message] => \" +  " + "_x.getMessage() " + ");");

                JCodeUtil.tryCatchBlock(jb, cm, typeAssert(v), stringCatch, this.settings.getScreenshotOnErrors(), logForPreviousMutation(), "AssertionError", this.settings.getScreenShotsPath());
            }

            numValidations++;

            if (numValidations != size) {
                jb.invoke(wait);
            }

        }

        JCodeUtil.newLine(jb);
    }

    /**
     * Generate the code for one assert
     * Change here if we want to add more onentry actions
     *
     * @param v
     * @return
     */
    public List<String> typeAssert(Validation v) {
        List<String> result = new ArrayList<>();
        String modelName = v.getId();

        // Get the VALUES from the MAPPING file
        Mapping map = this.mapping.get(modelName);

        // Check the type of the assert
        switch (v.getType()) {
            case "displayed?":
                result.add(JCodeUtil.assertTrue(map));
                result.add("Reporter.log(\"Element displayed? \" + driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).isDisplayed());");
                break;
            case "not_displayed?":
                result.add(JCodeUtil.assertNotTrue(map));
                result.add("Reporter.log(\"Element displayed? \" + !driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).isDisplayed());");
                break;
            case "enabled?":
                result.add("assertTrue(driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).isEnabled());");
                result.add("Reporter.log(\"[Validaton]: Element is Enabled  \" + driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).isEnabled());");
                break;
            case "disabled?":
                result.add("assertTrue(!driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).isEnabled());");
                result.add("Reporter.log(\"[Validaton]: Element is disabled  \" + !driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).isEnabled());");
                break;
            case "attribute":
                result.add("assertTrue(driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).getAttribute(\"" + map.getTypeOfAction() + "\").equals(\"" + this.values.get(modelName).getValues().get(0) + "\"));");
                result.add("Reporter.log(\"[Validaton]: Attribute \"+ driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).getAttribute(\"" + map.getTypeOfAction() + "\").equals(\"" + this.values.get(modelName).getValues().get(0) + "\"));");
                break;
            case "css":
                result.add("assertTrue(driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).getCssValue(\"" + map.getTypeOfAction() + "\").equals(\"" + this.values.get(modelName).getValues().get(0) + "\"));");
                result.add("Reporter.log(\"[Validaton]: Css Property is \" + driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).getCssValue(\"" + map.getTypeOfAction() + "\").equals(\"" + this.values.get(modelName).getValues().get(0) + "\"));");
                break;
            case "action":
                result.add("driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\"))." + map.getTypeOfAction() + "();") ;
                break;
            case "contains":
                result.add("assertTrue(driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).getText().toLowerCase().contains(\"" + this.values.get(modelName).getValues().get(0) + "\".toLowerCase()));");
                result.add("Reporter.log(\"[Validaton]: Text Contains? \" + driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).getText().toLowerCase().contains(\"" + this.values.get(modelName).getValues().get(0) + "\".toLowerCase()));");
                break;
            case "regex":
                result.add("assertTrue(driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).getText().matches(\"" + this.values.get(modelName).getValues().get(0) + "\"));");
                result.add("Reporter.log(\"[Validaton]: Text Contains? \" + driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).getText().matches(\"" + this.values.get(modelName).getValues().get(0) + "\"));");
                break;
            case "url":
                result.add("assertTrue(driver.getCurrentUrl().contains(\"" + this.values.get(modelName).getValues().get(0) + "\") );");
                result.add("Reporter.log(\"[Validaton] Location is correct \" + driver.getCurrentUrl() );");
                break;
            case "default":
                result.add("assertTrue(driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).getText().equals(\"" + this.values.get(modelName).getValues().get(0) + "\"));");
                result.add("Reporter.log(\"[Validaton]: Text is correct? \" + driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).getText().equals(\"" + this.values.get(modelName).getValues().get(0) + "\"));");
                break;
            case "is_selected":
                result.add("assertTrue(driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).isSelected());");
                result.add("Reporter.log(\"[Validaton]: Element is Selected  \" + driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).isSelected());");
                break;
            case "is_not_selected":
                result.add("assertTrue(!driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).isSelected());");
                result.add("Reporter.log(\"[Validaton]: Element is not selected  \" + !driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).isSelected());");
                break;
        }
        return result;
    }

    /**
     * Generate de code for the forms
     *   1 - Get Actions in Form (ex: field TextField)
     *   2 - Check for conditions
     *   3 - Submit Form
     *
     * @param form
     * @param jb
     */
    private int generateForm(Form form, JBlock jb, DefaultValues.typeOfMutations testType, JCodeModel cm, JMethod wait, boolean doMutation) {
        // Create the comment
        JCodeUtil.comment(jb, "Form " + form.getId());

        Mapping map;
        Value v;
        JVar var;
        JBlock formBlock = new JBlock();

        // Message for tests_generators.MUTATIONS
        String mutationMessage;

        List<Action> mutatedActions;

        /**
         * Check For all the Mutations
         */
        MutationResult mutationResult = mutationGenerator.checkFormMutations(testType, form, formBlock, this.testCaseMutated, doMutation);
        mutatedActions = mutationResult.getMutatedActions();

        if (testType == MUTATIONS_FROM_FILE) {
            mutationResult = mutationGenerator.formMutationFromFile(mutatedActions, this.values, this.mutations, formBlock);

            if (!"".equals(mutationResult.getMutationMessage())) {
                this.mutationFromFile = 1;
            }
        } else if (testType == MISTAKE_MUTATION) {
            mutationResult = mutationGenerator.mistakeMutationRandom(mutatedActions, this.mapping, this.values, form, formBlock, this.testCaseMutated, doMutation);
        }

        mutatedActions = mutationResult.getMutatedActions();
        this.testCaseMutated = mutationResult.getTestCaseMutated();
        mutationMessage = mutationResult.getMutationMessage();

        // Generate Actions
        for (Action a : mutatedActions) {
            map = this.mapping.get(a.getName());
            v = this.values.get(a.getName());
            String typeElement = a.getTypeElement();

            if (map == null || v == null) {
                logger.error("ERROR in Mapping Or Values! [" + a.getName() + "]");
                return -1;
            }

            // Check type of elements in the form
            switch (typeElement) {
                case "checkbox":
                    formBlock.directStatement("driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\"))." + map.getWhatToDo() + "();");
                    break;
                case "selectbox":
                    formBlock.directStatement("new Select(driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\"))).selectByVisibleText(\"" + v.getValues().get(0) + "\");");
                    break;
                default:
                    var = formBlock.decl(cm.ref(String.class).unboxify(), a.getName());
                    formBlock.directStatement("driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\"))." + map.getWhatToDo() + "(\"" + v.getValues().get(0) + "\");");
                    formBlock.directStatement(var.name() + " = driver.findElement(By." + map.getHowToFind() + "(\"" + map.getWhatToFind() + "\")).getAttribute(\"value\");");
            }
        }

        // LOG
        if (mutatedActions.size() != 0) {
            JCodeUtil.report(formBlock, "[Pass]: Form Filled " + form.getId());
        }

        // Check if test gonna fail
        formBlock.directStatement("gonnaFail = " + testCaseMutated + ";");

        // Get the MAPPING for the submit object
        map = this.mapping.get(form.getSubmit());
        StringBuilder s = new StringBuilder();

        JCodeUtil.tryCBWithMsgExcept(jb, cm, formBlock, "[FAIL]: Error in Form Fill " + form.getSubmit());

        // Create a conditional block
        JConditional jg = jb._if(JExpr.direct(s.toString().concat("gonnaFail == 1")));

        // Call on submit button
        checkTypeOfAction(jg._then(), map, form);

        // Generate the log for the submit button
        JCodeUtil.report(jg._then(), "[Pass]: Click Submit Button ");

        /**
         * Check for error state in case of Form fails
         */
        if (form.getError() != null) {
            // Add all the validations to the block
            JTryBlock jTryBlock = jg._then()._try();

            // Init The Catch
            JCatchBlock catchBlock = jTryBlock._catch(cm.ref(AssertionError.class));

            // Write the catch block with the fail of all validations to do in error state
            catchBlock.body().directStatement("Reporter.log(\"[Negative Test Fail]: Error on Validations in an error state\"); ");
            catchBlock.body().directStatement("fail(\"[Negative Test Fail]: Error on Validations in an error state \" + _x.getMessage() );");

            // Create add a comment
            jTryBlock.body().directStatement("// Started validation for an error page");

            // Write the body of TRY with all validation to do in error state
            for (Validation vError : form.getError().getValidations()) {
                // Generate assert
                List<String> assertsList = typeAssert(vError);
                for (String sta : assertsList) {
                    jTryBlock.body().directStatement(sta);
                }
            }
        }

        // Check if this test case have tests_generators.MUTATIONS
        if (testType != NORMAL) {

            if (!("".equals(mutationMessage))) {
                jg._then().directStatement(mutationMessage);
            }

            /** Check for mutation double click in a submit button
             * If we need to do the mutation, generate a double click
             * Else generate a simple click
             */
            if (testType == DOUBLE_CLICK_MUTATION && !form.getType().equals("alert")) {
                mutationResult = this.mutationGenerator.doubleClickSubmitMutation(cm, map, jb, this.varNumber++);
            } else {
                checkTypeOfAction(jb, map, form);
            }
        } else {
            checkTypeOfAction(jb, map, form);
            // JCodeUtil.report(jb, "[Pass]: Click Submit Button ");
        }

        // Generate the log for the submit button
        JCodeUtil.report(jb, "[Pass]: Click Submit Button ");

        // Return to stop
        jg._then()._return();
        jb.invoke(wait);

        // Check for validations after submit a Form
        if (form.hasValidations()) {
            int numValidations = 0;
            int size = form.getValidations().size();

            for (Validation val : form.getValidations()) {
                // Create a comment
                JCodeUtil.comment(jb, "Validation After a Form");

                List<String> stringCatch = new ArrayList<>();
                stringCatch.add("Reporter.log(\"[Fail]: In a Validation " + val.getId() + " after the form \");");
                stringCatch.add("fail(\" [Fail]: In a Validation [Message] => \" +  " + "_x.getMessage() " + ");");

                JCodeUtil.tryCatchBlock(jb, cm, typeAssert(val), stringCatch, this.settings.getScreenshotOnErrors(), logForPreviousMutation(), "AssertionError", this.settings.getScreenShotsPath());

                numValidations++;

                if (numValidations != size) {
                    jb.invoke(wait);
                }

            }
        }

        this.testCaseMutated = mutationResult.getTestCaseMutated();

        if (mutationResult.getTestCaseMutated() == 1) {
            // Gonna Fail
            return 1;
        } else {
            // Not Fail
            return 0;
        }
    }

    /** Auxiliary Methods */

    /**
     * Check the number of forms in the path and choose one to inject a mutation
     * @return one pair with the total of forms and the position to the mutation
     * */
    private int checkFormMutations(DefaultValues.typeOfMutations testType, List<Object> stepsOfPath){
        int formCount = 0;
        ArrayList<Integer> validPositions = new ArrayList<>();

        for(Object obj : stepsOfPath){
            if (obj instanceof Edge) {
                Edge edge = (Edge) obj;
                Event event = edge.getEvent();

                if(event instanceof Form) {
                    if (testType == SLIP_CALL) {
                        Form f = (Form) event;
                        if(f.getActions().size() > 1){
                            validPositions.add(formCount);
                        }
                    } else if (testType == MISTAKE_MUTATION) {
                        Form f = (Form) event;
                        for (Action a : f.getActions()) {
                            Mapping m = this.mapping.get(a.getName());

                            if ("sendKeys".equals(m.getWhatToDo())) {
                                validPositions.add(formCount);
                                break;
                            }
                        }
                    } else {
                        validPositions.add(formCount);
                    }
                    formCount++;
                }
            }
        }

        if (!validPositions.isEmpty()) {
            int position = RandomUtil.randomNumber(0, validPositions.size() - 1);
            return validPositions.get(position);
        }

        return 0;
    }

    private void checkForPreviousMutation(JBlock jb, int testCaseMutated) {
        // Log for check if is got any mutation on previous actions
        if (testCaseMutated == 1) {
            jb.directStatement("Reporter.log(\"Mutation not Killed\");");
        }
    }

    private String logForPreviousMutation() {
        // Log for check if is got any mutation on previous actions
        if (testCaseMutated == 1) {
            return "Reporter.log(\"Mutation Killed the test fails\");";
        } else {
            return "";
        }
    }

    private void checkTypeOfAction(JBlock jb, Mapping mapping, Form form) {
        // Generate the click on submit button
        if (!form.getType().equals("alert")) {
            JCodeUtil.clickAction(jb, mapping);
        } else {
            JCodeUtil.clickAlertAction(jb, mapping);
        }
    }

    public void setValues(Map<String, Value> inputValues) {
        this.values = inputValues;
    }
}
