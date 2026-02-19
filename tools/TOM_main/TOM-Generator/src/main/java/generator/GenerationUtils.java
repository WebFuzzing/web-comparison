package generator;

import generator.factories.*;
import org.jgrapht.Graph;
import org.jgrapht.alg.util.Pair;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import parsers.config.files.Mapping;
import parsers.config.files.Mutation;
import parsers.config.files.Value;
import parsers.model.files.IParserStrategy;
import parsers.model.files.graph.elements.Path;
// import generator.algorithms.GraphTraversal;
import generator.algorithms.ITraversalStrategy;
import generator.generators.CodeGenerator;
import generator.generators.Settings;
import utils.DefaultValues;
import utils.JGraphUtil;
import java.io.IOException;
import java.util.List;
import java.util.Map;

/**
 * Created by Marcelo Gonçalves
 */
public class GenerationUtils {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    private DefaultValues.typeOfTests typeOfTest;
    private DefaultValues.typeOfAlg typeOfAlg;
    private String pathFolder;
    private String modelName;
    private String initialNode;
    private String finalNode;
    private Map<String, ?> inputValues;
    private Map<String, Mutation> mutationsValues;
    private Map<String, Mapping> mapping;
    private String url;
    private int browser;

    private int numberOfTestsGenerated;
    private int numberOfTestsMutated;
    private int pathsSize;

    public GenerationUtils(DefaultValues.typeOfTests typeOfTest, DefaultValues.typeOfAlg typeOfAlg, String pathFolder, IParserStrategy parser,
                           Map<String, ?> inputValues, Map<String, Mutation> mutationsValues, Map<String, Mapping> mapping, String url, int browser) {
        this.typeOfTest = typeOfTest;
        this.typeOfAlg = typeOfAlg;
        this.pathFolder = pathFolder;
        this.modelName = parser.getModelName();
        this.initialNode = parser.getInitialNode();
        this.finalNode = parser.getFinalNode();
        this.inputValues = inputValues;
        this.mutationsValues = mutationsValues;
        this.mapping = mapping;
        this.url = url;
        this.browser = browser;
        this.numberOfTestsGenerated = 0;
        this.numberOfTestsMutated = 0;
    }

    // Add new algorithms here
    private ITraversalStrategy getStrategy(Graph newGraph, int maxVertexVisit, int maxEdgeVisit) {
        AlgorithmsAbstractFactory algorithmsAbstractFactory = FactoryProducer.getAlgFactory(typeOfAlg);

        ITraversalStrategy strategy;

        switch (typeOfAlg) {
            case DFS:
                DFSFactory dfsFactory = (DFSFactory) algorithmsAbstractFactory;
                strategy = dfsFactory.getAllPathsDFS(newGraph, initialNode, finalNode, maxVertexVisit, maxEdgeVisit);
                break;
            case BFS:
                BFSFactory bfsFactory = (BFSFactory) algorithmsAbstractFactory;
                strategy = bfsFactory.getAllPathsBFS(newGraph, initialNode, finalNode, maxVertexVisit, maxEdgeVisit);
                break;
            default:
                return null;
        }
        return strategy;
    }

    public Pair<String, List<Path>> generatePaths(Graph newGraph, String filePath, int maxVertexVisit, int maxEdgeVisit) throws OutOfMemoryError {

        // Create a graph traversal with a the chosen strategy
        //GraphTraversal graphStrategy = new GraphTraversal(
        ITraversalStrategy graphStrategy =  getStrategy(newGraph, maxVertexVisit, maxEdgeVisit);

        // Execute the graph traversal strategy and return all the paths
        List<Path> paths;
        String message = "Generation Complete!";

        try {
            paths = graphStrategy.execute(newGraph);
            this.pathsSize = paths.size();
        } catch (OutOfMemoryError e) {
            logger.error("Out Of Memory Error: " + e);
            message = "Out Of Memory Error in Paths Search!";
            paths = graphStrategy.getOutOfMemoryPaths();
            this.pathsSize = graphStrategy.getOutOfMemoryPathsNumber();
        }

        if (paths.size() < 300000) {
            try {
                JGraphUtil.writePathsToFile(filePath, modelName, paths);
            } catch (OutOfMemoryError | IOException e) {
                logger.error("Out Of Memory Error on Write Paths to file: " + e);
            }
        }

        return new Pair<>(message, paths);
    }

    public boolean testGeneration(List<Path> paths, List<DefaultValues.typeOfMutations> testTypes) throws OutOfMemoryError {

        GeneratorAbstractFactory generatorAbstractFactory = FactoryProducer.getTestTypeFactory(typeOfTest);

        CodeGenerator codeGenerator;

        switch (typeOfTest) {
            case IRIT:
                IRITFactory iritFactory = (IRITFactory) generatorAbstractFactory;
                codeGenerator = iritFactory.getGenerator((Map<String, Value>) inputValues, mutationsValues, modelName, pathFolder);
                break;
            case WEB:
                WEBFactory webFactory = (WEBFactory) generatorAbstractFactory;
                // Settings - browser, screenshot_on_errors, check_for_broken_links, check_for_broken_images
                Settings settings = new Settings(browser, 1, 1, 1, modelName, pathFolder);
                codeGenerator = webFactory.getGenerator((Map<String, Value>) inputValues, mutationsValues, mapping, settings, url);
                break;
            case JSON:
                JSONFactory jsonFactory = (JSONFactory) generatorAbstractFactory;
                codeGenerator = jsonFactory.getGenerator(pathFolder + "JSONTests/");
                break;
            default:
                return false;
        }

        try {
            codeGenerator.testsGeneration(paths, testTypes);
        } finally {
            this.numberOfTestsGenerated = codeGenerator.getNumberOfTestsGenerated();
            this.numberOfTestsMutated = codeGenerator.getNumberOfTestsMutated();
        }

        return true;
    }

    public int getNumberOfTestsGenerated(){
        return this.numberOfTestsGenerated;
    }

    public int getNumberOfTestsMutated(){
        return this.numberOfTestsMutated;
    }

    public int getPathsSize(){ return this.pathsSize; }

}
