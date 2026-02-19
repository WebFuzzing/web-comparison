package parsers.model.files;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;

/**
 * Do the parse of the hamsters xml file and transform in a csv file with the information of the tasks
 *
 * @author Marcelo Gonçalves
 */
public class ParserHamstersXML {

    private static FileWriter writer;
    private static ArrayList<Element> visitedTasks;
    private static final String FILE_MODEL = "/Users/mgonc/Desktop/Model_v8/Avoid.xml";
    private static final String CSV_FILE = "/Users/mgonc/Desktop/Model_v8/Avoid.csv";

    private ParserHamstersXML() {
        throw new IllegalAccessError("Utility class");
    }

    public static void write(Element parent, Element child) throws IOException {

        if (parent != null) {
            writer.append(parent.getAttribute("id") + ","
                    + child.getAttribute("id") + ","
                    + child.getAttribute("name") + ","
                    + child.getAttribute("type") + ","
                    + child.getAttribute("subtree") + "\n");
        } else {
            writer.append(","
                    + child.getAttribute("id") + ","
                    + child.getAttribute("name") + ","
                    + child.getAttribute("type") + ","
                    + child.getAttribute("subtree") + "\n");
        }

    }

    public static void parseTask(Element parent, Element child) throws IOException {

        if ("abstract".equals(child.getAttribute("type")) || "subroutine".equals(child.getAttribute("type"))) {

            write(parent, child);
            visitedTasks.add(child);

            NodeList op = child.getElementsByTagName("task");

            for (int z = 0; z < op.getLength(); z++) {
                Element childTask = (Element) op.item(z);

                if (!visitedTasks.contains(childTask)) {
                    parseTask(child, childTask);
                }
            }

        } else {
            write(parent, child);
            visitedTasks.add(child);
        }
    }


    /**
     * @param path    path of the MODEL file
     * @param csvFile path to the csv file
     */
    public static void parsing(String path, String csvFile) throws ParserConfigurationException, IOException, SAXException {

        writer = new FileWriter(csvFile);
        visitedTasks = new ArrayList<>();

        // Construct document builder
        File stocks = new File(path);
        DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
        DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
        Document doc = dBuilder.parse(stocks);
        doc.getDocumentElement().normalize();

        // Get all the tasks in xml
        NodeList tasksList = doc.getElementsByTagName("task");

        // Write the tasks to the csv file
        writer.append("ParentId" + "," + "Id" + "," + "Name" + "," + "Type" + "," + "Subtree\n");

        for (int i = 0; i < tasksList.getLength(); i++) {
            Element element = (Element) tasksList.item(i);

            if (!visitedTasks.contains(element)) {
                parseTask(null, element);
            }

        }

        writer.flush();
        writer.close();
    }

    public static void main(String[] args) throws IOException, SAXException, ParserConfigurationException {
        parsing(FILE_MODEL, CSV_FILE);
    }
}