import glob
import pprint
import sys
from collections import defaultdict
import statistics
import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt
import scipy.stats as stats


def get_list_item_counts(l: list[str]) -> list[int]:
    counts = {}
    for i in l:
        counts[i] = l.count(i)
    return list(counts.values())

def get_project_wise_data(path):
    files = [f for f in glob.glob(path) if "actions" not in f]
    results = defaultdict(lambda: {'total_unique_failures': [], 'minute_wise_detection_rates': [], 'code_coverages': []}, {})
    for file in files:
        project_code = file.split('/')[-1].split('_')[0]
        per_minute_failures = []
        #
        # if project_code != 'trello':
        #     continue
        # print(file)
        before_finished_content, after_finished_content = open(file, 'r').read().split('WARNING  Finished', 1)
        failure_lines = [line for line in
                         before_finished_content.split('WARNING  Starting')[1].split('\n')[: -1] if
                         "ERROR    Failure" in line and len(line)]

        reported_unique_failures = [f[19:] for f in
                                    after_finished_content.split('WARNING  (Failure Count, Coverage Percentage)')[
                                        1].split('\n')[1:]
                                    if len(f) and f[0] != '~']

        coverage = float(after_finished_content.split('WARNING  (Failure Count, Coverage Percentage)')[
                                        1].split('\n')[0].split(',')[1][: -1])
        results[project_code]['code_coverages'].append(coverage)
        # actions = ["".join(line.split(',')[1:-2]).strip() for line in
        #            open(f'{file.split(".log")[0]}_actions.log', 'r').readlines()]

        curr_minute = int(failure_lines[0][14:16])
        parsed_unique_failures = []

        for failure_line in failure_lines:
            minute = int(failure_line[14:16])
            failure_text = failure_line[19:].replace('Failure: ', '')
            if minute != curr_minute:
                per_minute_failures = per_minute_failures + ([len(parsed_unique_failures)] * (minute - curr_minute))
                curr_minute = minute
            if failure_text not in parsed_unique_failures and failure_text in reported_unique_failures:
                parsed_unique_failures.append(failure_text)
        if len(per_minute_failures) < 30:
            per_minute_failures = per_minute_failures + (
                        [len(parsed_unique_failures)] * (30 - len(per_minute_failures)))

        # print(file, len(per_minute_failures), per_minute_failures)
        results[project_code]['total_unique_failures'].append(per_minute_failures[29])
        results[project_code]['minute_wise_detection_rates'].append(per_minute_failures[:30])
        # results.append({
        #     'project': project_code,
        #     'total_failure': len(failure_lines),
        #     'unqiue_failures': " ".join([str(f) for f in permi_nute_failures]),
        # })
    return results


ebat_results = get_project_wise_data("./logs/ebat/*")
webexplor_results = get_project_wise_data("./logs/webExplor/*")

means_ebat = []
means_webexplor = []
for project in ebat_results.keys():
    means_ebat.append((statistics.mean(ebat_results[project]['total_unique_failures']), statistics.mean(ebat_results[project]['code_coverages'])))
    means_webexplor.append((statistics.mean(webexplor_results[project]['total_unique_failures']), statistics.mean(webexplor_results[project]['code_coverages'])))

    print(f"#Failure ({project}) -> eBAT: ({means_ebat[-1][0]}, {statistics.stdev(ebat_results[project]['total_unique_failures'])}), webExplor: ({means_webexplor[-1][0]}, {statistics.stdev(webexplor_results[project]['total_unique_failures'])})")
    print(f"Code Coverage ({project}) -> eBAT: ({means_ebat[-1][1]}, {statistics.stdev(ebat_results[project]['code_coverages'])}), webExplor: ({means_webexplor[-1][1]}, {statistics.stdev(webexplor_results[project]['code_coverages'])})\n")
    # print(f"eBAT: {project} => (mean, std) = ({means_ebat[project][-1]}, {statistics.stdev(ebat_results[project]['total_unique_failures'])})")
    # print(f"webExplor: {project} => (mean, std) = ({means_webexplor[project][-1]}, {statistics.stdev(ebat_results[project]['total_unique_failures'])})\n")
# print(ebat_results['dimeshift']['total_unique_failures'][0])
# print(webexplor_results['dimeshift']['total_unique_failures'][0])

print(f"\n\nAverage unique failures: eBAT: {statistics.mean([x[0] for x in means_ebat])}, webExplor: {statistics.mean([x[0] for x in means_webexplor])}")
print(f"\n\nAverage code coverages: eBAT: {statistics.mean([x[1] for x in means_ebat])}, webExplor: {statistics.mean([x[1] for x in means_webexplor])}")


# statistical test
print('\n\nStatistical test for unique failure detection -')
for project in ebat_results.keys():
    U1, p  = stats.mannwhitneyu(ebat_results[project]['total_unique_failures'], webexplor_results[project]['total_unique_failures'], alternative='greater')
    print(f"{project}: (U = {U1}, p = {p})")

print('\nStatistical test for code coverage-')
for project in ebat_results.keys():
    U1, p  = stats.mannwhitneyu(ebat_results[project]['code_coverages'], webexplor_results[project]['code_coverages'], alternative='greater')
    print(f"{project}: (U = {U1}, p = {p})")



# generate detection rate graphs
sns.set(font_scale = 1.2)
for project in ebat_results.keys():

    df_rows = []
    for minute_wise_failures in ebat_results[project]['minute_wise_detection_rates']:
        for index, num_fail in enumerate(minute_wise_failures):
            df_rows.append(['eBAT', index+1, num_fail])
    for minute_wise_failures in webexplor_results[project]['minute_wise_detection_rates']:
        for index, num_fail in enumerate(minute_wise_failures):
            df_rows.append(['webExplor', index+1, num_fail])
    df_rows = df_rows + [['eBAT', 0, 0], ['webExplor', 0, 0]]
    df = pd.DataFrame(df_rows, columns=['Approach', 'Time (in Minutes)', '# Unique Failures'])
    p = sns.lineplot(data=df, x="Time (in Minutes)", y="# Unique Failures", hue="Approach")
    # plt.title(project, weight='bold').set_fontsize('14')
    plt.ylabel(f'#failures ({project})', weight='bold').set_fontsize('16')
    plt.xlabel('')
    p.set_xticks([0, 5, 15, 30])
    p.set_xticklabels(['0', '5min', '15min', '30min'])
    plt.xlim(0, 30)
    plt.legend(loc='lower center')
    plt.savefig(f"./plots/{project}.png")
    plt.clf()

