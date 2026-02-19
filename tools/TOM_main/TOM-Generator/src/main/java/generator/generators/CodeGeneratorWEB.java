package generator.generators;

import com.sun.codemodel.*;
import org.openqa.selenium.WebDriver;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import parsers.config.files.Mapping;
import parsers.config.files.Mutation;
import parsers.config.files.Value;
import parsers.model.files.graph.elements.Path;
import utils.DefaultValues;
import utils.JCodeUtil;
import java.io.IOException;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * @author raphaelrodrigues
 */
public class CodeGeneratorWEB implements CodeGenerator {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    private String nameClass;

    // Configurations Values
    private final Settings settings;

    private final StaticCode staticCode;
    private final TestGeneratorWEB testGeneratorWEB;

    private final String url;

    // Auxiliary variables
    private JDefinedClass jDefinedClass;
    private JMethod methodBrokenImages;
    private JMethod methodBrokenLinks;
    private JDocComment jDocComment;
    private JMethod mInitSelenium;
    private JMethod wait;
    private int totalOfTests;
    private int totalOfTestsMutated;
    private Map<String, Value> values;

    public CodeGeneratorWEB(Map<String, Value> values, Map<String, Mutation> mutations, Map<String, Mapping> mapping, Settings settings, String url) {

        this.url = url;

        Map<String, Mapping> mappingValues = new HashMap<>();

        for(Map.Entry<String,Mapping> entry : mapping.entrySet()){
            mappingValues.put(entry.getKey(), new Mapping(entry.getValue()));
        }

        Map<String, Value> inputValues = new HashMap<>();
        this.values = new HashMap<>();

        for(Map.Entry<String,Value> entry : values.entrySet()){
            inputValues.put(entry.getKey(), new Value(entry.getValue()));
            this.values.put(entry.getKey(), entry.getValue().clone());
        }

        this.settings = settings;
        this.staticCode = new StaticCode(settings);
        this.testGeneratorWEB = new TestGeneratorWEB(inputValues, mutations, mappingValues, settings);
        this.totalOfTests = 0;
    }

    private void generateAuxiliaryMethods(JCodeModel jCodeModel, JDefinedClass jDefinedClass) {
        // Creates a method wait()
        wait = JCodeUtil.wait(jDefinedClass);

        // Generate method isElementDisplayed
        staticCode.generateIsElementDisplayed(jCodeModel, jDefinedClass);

        // Generate method CheckBrokenLinks
        methodBrokenLinks = staticCode.generateCheckBroken(jCodeModel, jDefinedClass, "Links");

        // Generate method CheckBrokenImages
        methodBrokenImages = staticCode.generateCheckBroken(jCodeModel, jDefinedClass, "Images");
    }

    private boolean generateInitialCode(JCodeModel jCodeModel, List<Path> paths) {

        try {
            // Create the class name
            jDefinedClass = jCodeModel._class(this.nameClass);
        } catch (JClassAlreadyExistsException e) {
            logger.error("An error occur initializing the test case generation! " + e);
            jDefinedClass = null;
            return false;
        }

        JType webDriverType = jCodeModel._ref(WebDriver.class);

        // Generate private webDriverType
        jDefinedClass.field(JMod.PRIVATE, webDriverType, "driver");

        // Add class level comment
        jDocComment = jDefinedClass.javadoc();
        jDocComment.add("Test Class for " + paths.toString() + "\n");
        jDocComment.add("Generated in " + JCodeUtil.getCurrentDate() + "\n");

        // Create method to init variables for selenium WebDriver
        mInitSelenium = jDefinedClass.method(1, void.class, " initSelenium");

        JMethod mStartTest = jDefinedClass.method(1, void.class, "startTest");
        staticCode.startTest(jCodeModel, mStartTest);

        generateAuxiliaryMethods(jCodeModel, jDefinedClass);

        return true;
    }

    private boolean concludeCodeGeneration(JCodeModel jCodeModel, List<Path> paths, String url, int numOfTests, int i) {
        // Add comment with number of test cases
        jDocComment.add("Number of test cases ==> " + numOfTests + " for " + paths.size() + " Groups(Found Paths)");

        // Generate a method that init the WebDriver
        staticCode.initSelenium(mInitSelenium, url, settings, i);

        // Generate the afterTest Functions
        staticCode.closeSelenium(jDefinedClass, i);

        try {
            // Create the file
            staticCode.closeFile(jCodeModel, settings.getTestsPath());

            // Write the necessary imports at the begin of the file
            staticCode.initImports(settings.getTestsPath() + this.nameClass);
        } catch (IOException e) {
            logger.error("An error occur concluding the test case generation! Iteration number: " + i + "; " + e);
            return false;
        }

        return true;
    }

    private Map<String, Value> cloneInputValues(){
        Map<String, Value> inputValues = new HashMap<>();
        for(Map.Entry<String,Value> entry : this.values.entrySet()){
            inputValues.put(entry.getKey(), new Value(entry.getValue()));
        }
        return inputValues;
    }

    /**
     * This method construct all the files of tests.
     *
     * @param url
     * @param paths
     * @param testType
     */
    private int generate(String url, List<Path> paths, DefaultValues.typeOfMutations testType) {
        int i = 1;
        int numOfTests = 0;

        testGeneratorWEB.setValues(cloneInputValues());

        JCodeModel jCodeModel = new JCodeModel();

        boolean flag = generateInitialCode(jCodeModel, paths);

        if (flag) {
            // For each path create a method with all the steps to test
            for (Path p : paths) {
                // Go through each step of the path
                numOfTests += testGeneratorWEB.generateTest(jCodeModel, jDefinedClass, p, methodBrokenLinks, methodBrokenImages, wait, testType, i++);

                if (testType == DefaultValues.typeOfMutations.MISTAKE_MUTATION) {
                    testGeneratorWEB.setValues(cloneInputValues());
                }

            }
            flag = concludeCodeGeneration(jCodeModel, paths, url, numOfTests, i);
        }

        // The file has no tests
        if (jDefinedClass.methods().size() == 7) {
            staticCode.deleteFile(settings.getTestsPath() + this.nameClass + ".java");
            return 0;
        }

        if (flag) {
            return numOfTests;
        }

        logger.error("An error occur at test case generation! Test Type: " + testType + ";");

        return 0;
    }

    @Override
    public void testsGeneration(List<Path> paths, List<DefaultValues.typeOfMutations> testTypes) {
        int i = 0;

        testTypes.remove(DefaultValues.typeOfMutations.LAPSE_CALL);

        for (DefaultValues.typeOfMutations testType : testTypes) {
            this.nameClass = "WebTests_" + testType.toString();
            int testGenerated = generate(url, paths, testTypes.get(i));

            if(!testType.equals(DefaultValues.typeOfMutations.NORMAL)){
                totalOfTestsMutated += testGenerated;
            }

            totalOfTests += testGenerated;

            i++;
        }
    }

    @Override
    public int getNumberOfTestsGenerated() {
        return totalOfTests;
    }

    @Override
    public int getNumberOfTestsMutated() {
        return totalOfTestsMutated;
    }
}
