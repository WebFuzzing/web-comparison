package parsers.config.files;

import com.google.gson.stream.JsonReader;
import com.google.gson.stream.MalformedJsonException;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import utils.DefaultValues;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

/**
 * @author raphaelrodrigues
 *
 * Example of STRUTURE OF THE OBJECT
 *      "form_email": {
 *          "how_to_find":"id",
 *          "what_to_find":"signin-email",
 *          "what_to_do":"send_keys",
 *          "type_of_action":"textbox",
 *          "verification":"exists? "
 *      }
 */
public class ParseMappingFile implements IParseFile {

    private static final Logger logger = LoggerFactory.getLogger(DefaultValues.APP_CLASS);

    /**
     * Get the VALUES from the MAPPING file
     *
     * @param path
     * @return
     * @throws IOException
     */
    @Override
    public Map<String, Mapping> getValues(String path) throws IOException {

        HashMap<String, Mapping> mapping = new HashMap<>();
        String name;
        String hashName;
        Mapping map;
        try {
            // Read the file
            try (JsonReader reader = new JsonReader(new FileReader(path))) {
                reader.beginObject();

                while (reader.hasNext()) {
                    // Read every object in file
                    hashName = reader.nextName(); // Get the name in MODEL

                    reader.beginObject();

                    // Create the object to store the VALUES
                    map = new Mapping();
                    while (reader.hasNext()) {
                        name = reader.nextName();       // Get the name of attribute(ex:how_to_find)
                        String s = reader.nextString(); // Get the value of attribute

                        // Check witch attribute is and set the object Mapping
                        switch (name) {
                            case "how_to_find":
                                map.setHowToFind(s);
                                break;
                            case "what_to_find":
                                map.setWhatToFind(s);
                                break;
                            case "what_to_do":
                                map.setWhatToDo(s);
                                break;
                            case "type_of_action":
                                map.setTypeOfAction(s);
                                break;
                            case "wait?":
                                map.setWait(s);
                                break;
                            default:
                                logger.error("Wrong Attribute in Mapping File!!");
                        }
                    }
                    // Put the object in the hashmap
                    mapping.put(hashName, map);
                    reader.endObject();
                }

                reader.endObject();
            }
        } catch (FileNotFoundException e) {
            logger.error("Mapping File Not Found! " + e);
            return null;
        } catch (MalformedJsonException e) {
            logger.error("Mapping File with Errors " + e);
            return null;
        }

        return mapping;
    }
}