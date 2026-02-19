package generator.generators;

import com.google.gson.stream.JsonWriter;
import org.apache.commons.io.IOUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import parsers.model.files.graph.elements.Edge;
import parsers.model.files.graph.elements.Event;
import parsers.model.files.graph.elements.Path;
import utils.DefaultValues;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.List;

/**
 * Created by Marcelo Gonçalves
 */
public class CodeGeneratorJSON implements CodeGenerator {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    private String generationPath;

    private int totalOfTests;
    private int totalOfTestsMutated;

    public CodeGeneratorJSON(String folderDest) {
        this.generationPath = folderDest;
        this.totalOfTests = 0;
        this.totalOfTestsMutated = 0;
    }

    private void writeEdge(JsonWriter jsonWriter, Edge edge, long time) throws IOException {
        Event event = edge.getEvent();
        jsonWriter.beginObject();

        jsonWriter.name("id");
        jsonWriter.value(event.getId());
        jsonWriter.name("name");
        jsonWriter.value(event.getModelName());
        jsonWriter.name("ts");
        jsonWriter.value(time);

        jsonWriter.endObject();
    }

    /**
     * Go through each step in the path
     * for each path there will be one json file
     *
     * @param p
     * @param filename
     */
    public void generate(Path p, String filename) {

        long time = System.currentTimeMillis();
        new File(this.generationPath).mkdirs();
        JsonWriter jsonWriter = null;
        FileWriter fileWriter = null;

        try {
            fileWriter = new FileWriter(this.generationPath + filename + ".json");
            jsonWriter = new JsonWriter(fileWriter);
            jsonWriter.setIndent("\t");
            jsonWriter.beginArray();
            for (Object obj : p.getSteps()) {
                if(obj instanceof Edge){
                    writeEdge(jsonWriter, (Edge) obj, time);
                    time++;
                }
            }
            jsonWriter.endArray();
        } catch (IOException e) {
            logger.error("Error Copying the Json Array to the File! " + e);
        } finally {
            IOUtils.closeQuietly(jsonWriter);
            IOUtils.closeQuietly(fileWriter);
        }
    }

    @Override
    public void testsGeneration(List<Path> paths, List<DefaultValues.typeOfMutations> testTypes) {
        int number = 0;

        testTypes.remove(DefaultValues.typeOfMutations.MISTAKE_MUTATION);
        testTypes.remove(DefaultValues.typeOfMutations.DOUBLE_CLICK_MUTATION);
        testTypes.remove(DefaultValues.typeOfMutations.REMOVE_REQUIRED_FIELD);
        testTypes.remove(DefaultValues.typeOfMutations.DOUBLE_CLICK_CALL);
        testTypes.remove(DefaultValues.typeOfMutations.DOUBLE_CLICK_MENU_CALL);
        testTypes.remove(DefaultValues.typeOfMutations.INJECT_BACK_EVENT);
        testTypes.remove(DefaultValues.typeOfMutations.INJECT_REFRESH_EVENT);

        for (Path p : paths) {
            generate(p, "Test" + number);
            number++;
            this.totalOfTests++;
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
