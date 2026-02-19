package generator;

import org.jgrapht.Graph;
import utils.DefaultValues;
import utils.JGraphUtil;

import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;
import java.util.List;

/**
 * Created by Marcelo Gonçalves
 */
public class StartGenerator {

    public static void main(String[] args) throws IOException, XPathExpressionException {

        Generator g = new Generator(DefaultValues.testType.name(), DefaultValues.FILE_VALUES, DefaultValues.FILE_MUTATIONS, DefaultValues.FILE_MAP);

        Graph newGraph = g.parseModelFile(DefaultValues.FILE_MODEL, DefaultValues.modelType.name(), DefaultValues.FOLDER_GEN,
                DefaultValues.testType, DefaultValues.algType, DefaultValues.URL, DefaultValues.BROWSER);

        long startTime = System.nanoTime();

        JGraphUtil.generateFile(DefaultValues.FOLDER_GEN, "", newGraph);

        List paths = g.generatePaths(newGraph, DefaultValues.FOLDER_GEN,
                DefaultValues.MAX_VERTEX_VISIT, DefaultValues.MAX_EDGE_VISIT).getSecond();

        g.generateTests(paths, DefaultValues.testTypesToGenerate);

        long stopTime = System.nanoTime();

        long elapsedTime = stopTime - startTime;

        System.out.println("\nTime spent in milliseconds: " + elapsedTime/1000000);
        System.out.println("Number of Paths found: " + g.getPathsSize());
        System.out.println("Number of Tests Generated: " + g.getNumberOfTestsGenerated());
        System.out.println("Number of Tests Mutated: " + g.getNumberOfTestsMutated() + "\n");
    }

}
