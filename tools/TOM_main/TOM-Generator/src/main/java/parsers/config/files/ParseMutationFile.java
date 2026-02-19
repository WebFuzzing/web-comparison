package parsers.config.files;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import org.apache.commons.io.IOUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import utils.DefaultValues;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

/**
 * Structure of the file
 * [
 *      {
 *          "type": "lapse_form",
 *          "model_element" : "email",
 *          "value" : "t43"
 *          "fail" : "t43"
 *      }
 *    or
 *      {
 *          "id"    : "4",
 *          "type": "lapse_form",
 *          "model_element" : "email",
 *          "target" : "t43",
 *      }
 * ]
 */
public class ParseMutationFile implements IParseFile {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    /**
     * Get the VALUES from the mutation file
     *
     * @param path
     * @return
     * @throws IOException
     */
    @Override
    public Map<String, Mutation> getValues(String path) throws IOException {

        HashMap<String, Mutation> mutations = new HashMap<>();

        InputStream is = new FileInputStream(path);
        String jsonTxt = IOUtils.toString(is);
        JsonArray jsonArray = new JsonParser().parse(jsonTxt).getAsJsonArray();

        Iterator it = jsonArray.iterator();

        while (it.hasNext()) {
            JsonElement st = (JsonElement) it.next();
            JsonObject obj = st.getAsJsonObject();

            Mutation mutation = new Mutation();
            String key = "";

            for (Map.Entry<String, JsonElement> elem : obj.entrySet()) {
                JsonElement jElement = elem.getValue();
                String value = jElement.getAsString();

                switch (elem.getKey()) {
                    case "id":
                        key = value;
                        mutation.setId(value);
                        break;
                    case "type":
                        mutation.setTypeOfMutation(value);
                        break;
                    case "model_element":
                        mutation.setModelElement(value);
                        break;
                    case "target":
                    case "value":
                        mutation.setValue(value);
                        break;
                    case "fail":
                        mutation.setGonnaFail(Integer.parseInt(value));
                        break;
                    default:
                        logger.error("Wrong Attribute in Mutation File!!");
                }
            }

            // Put the object in the hash map
            if ("".equals(key)) {
                mutations.put(mutation.getModelElement(), mutation);
            } else {
                mutations.put(key, mutation);
            }
        }

        return mutations;
    }
}
