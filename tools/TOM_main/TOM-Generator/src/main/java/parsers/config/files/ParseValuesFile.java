package parsers.config.files;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import org.apache.commons.io.IOUtils;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.util.*;

/**
 * Structure of the file
 *  [
 *      {
 *          "Id" : [
 *              "Value1",
 *              "Value2",
 *              ...
 *          ]
 *      }
 *      or
 *      {
 *          "Id" : "Value1"
 *      }
 * ]
 */
public class ParseValuesFile implements IParseFile {
    /**
     * Get the VALUES from the Values file
     *
     * @param path
     * @return
     * @throws IOException
     */
    @Override
    public Map<String, Value> getValues(String path) throws IOException {

        HashMap<String, Value> values = new HashMap<>();

        InputStream is = new FileInputStream(path);
        String jsonTxt = IOUtils.toString(is);
        JsonArray jsonArray = new JsonParser().parse(jsonTxt).getAsJsonArray();

        Iterator it = jsonArray.iterator();

        while (it.hasNext()) {
            JsonElement st = (JsonElement) it.next();
            JsonObject obj = st.getAsJsonObject();

            for (Map.Entry<String, JsonElement> elem : obj.entrySet()) {
                List list = new ArrayList<>();

                if (elem.getValue().isJsonArray()) {
                    JsonArray value = elem.getValue().getAsJsonArray();
                    Iterator valueIt = value.iterator();

                    while (valueIt.hasNext()) {
                        JsonElement jElement = (JsonElement) valueIt.next();
                        list.add(jElement.getAsString());
                    }
                } else {
                    list.add(elem.getValue().getAsString());
                }

                Value v = new Value(elem.getKey(), list);
                values.put(elem.getKey(), v);
            }
        }
        return values;

    }

}
