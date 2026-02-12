from hashlib import md5

def merge_intervals(ranges: list[tuple[int, int]]):
    ranges.sort(key=lambda x: x[0])

    # array to hold the merged intervals
    res = []
    s = -1000000000
    max = -1000000000
    for i in range(len(ranges)):
        a = ranges[i]
        if a[0] > max:
            if i != 0:
                res.append((s, max))
            max = a[1]
            s = a[0]
        else:
            if a[1] >= max:
                max = a[1]

    # 'max' value gives the last point of
    # that particular interval
    # 's' gives the starting point of that interval
    # 'm' array contains the list of all merged intervals

    if max != -100000 and [s, max] not in res:
        res.append((s, max))

    return res

def md5_hash(strs: list[str]):
    m = md5()
    for s in strs:
        m.update(s.encode())
    return int(m.hexdigest(), 16)