package generator;

import org.jgrapht.Graph;
import org.jgrapht.alg.util.Pair;
import parsers.config.files.*;
import parsers.model.files.IParserStrategy;
import parsers.model.files.ParserEMDL;
import parsers.model.files.ParserSCXML;
import parsers.model.files.ParserStrategy;
import parsers.model.files.graph.elements.Path;
import utils.DefaultValues;
import utils.JGraphUtil;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * Created by Marcelo Gonçalves
 */
public class Generator {

    private GenerationUtils gUtils;
    private Map<String, ?> inputValues;
    private Map<String, Mutation> mutationsValues;
    private Map<String, Mapping> mappingValues;

    public Generator(String type, String values, String mutations, String mapping) throws IOException {
        IParseFile valuesParser = new ParseValuesFile();
        IParseFile mutationParser = new ParseMutationFile();
        IParseFile mappingParser = new ParseMappingFile();

        this.inputValues = new HashMap<>();
        this.mutationsValues = new HashMap<>();
        this.mappingValues = new HashMap<>();

        this.gUtils = null;

        // Parse Configurations Files
        if (!"".equals(values)) {
            this.inputValues = valuesParser.getValues(values);
        }
        if (!"".equals(mutations)) {
            this.mutationsValues = (Map<String, Mutation>) mutationParser.getValues(mutations);
        }
        if (!"".equals(mapping) && "WEB".equals(type)) {
            this.mappingValues = (Map<String, Mapping>) mappingParser.getValues(mapping);
        }
    }

    private IParserStrategy getModelParser(String fileParser) {
        IParserStrategy parser;

        switch (DefaultValues.typeOfParser.valueOf(fileParser)) {
            case XML:
                parser = new ParserSCXML();
                break;
            case EMDL:
                parser = new ParserEMDL();
                break;
            default:
                return null;
        }

        return parser;
    }

    public Graph parseModelFile(String model, String fileParser, String folderGen,
                                       DefaultValues.typeOfTests testType, DefaultValues.typeOfAlg algType, String url, int browser) throws IOException, XPathExpressionException {

        IParserStrategy parser = getModelParser(fileParser);

        Graph newGraph = getGraphFromModel(model, parser);

        this.gUtils = new GenerationUtils(testType, algType, folderGen, parser,
                inputValues, mutationsValues, mappingValues, url, browser);

        return newGraph;
    }

    private Graph getGraphFromModel(String modelFile, IParserStrategy strategy) {
        // Do the parsing of the MODEL
        ParserStrategy parser = new ParserStrategy(strategy);

        // Create the Graph with the strategy parsers
        return parser.executeStrategy(modelFile);
    }

    public Pair<String, List<Path>> generatePaths(Graph newGraph, String folder, int maxVertexVisit, int maxEdgeVisit) throws OutOfMemoryError{
        return gUtils.generatePaths(newGraph, folder, maxVertexVisit, maxEdgeVisit);
    }

    public boolean generateTests(List<Path> paths, List<DefaultValues.typeOfMutations> testTypes) {
        return gUtils.testGeneration(paths, testTypes);
    }

    public int getNumberOfTestsGenerated(){
        return gUtils.getNumberOfTestsGenerated();
    }

    public int getNumberOfTestsMutated(){
        return gUtils.getNumberOfTestsMutated();
    }

    public int getPathsSize(){
        return gUtils.getPathsSize();
    }

    /**
     * Compare both models and calculate the difference
     */
    private List<Path> calculateDifferences(Graph nGraph, Graph pGraph, List<Path> paths, String iNode)
            throws IOException, XPathExpressionException {

        List<Path> differences = JGraphUtil.compare(nGraph, pGraph, iNode);
        List<Path> result = new ArrayList<>();

        for (Path p : paths) {
            if (checkPath(p, differences)) {
                result.add(p);
            }
        }

        return result;
    }

    private boolean checkPath(Path p, List<Path> differences) {
        for (Path o : differences) {
            if (o.subpathOf(p)) {
                return true;
            }
        }
        return false;
    }
}
