<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
    <div class="container mx-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                   <th>
                        Excel Customer Header
                   </th>
                   <th>
                        Customer Table Header
                   </th>
                </tr>
            </thead>
            <tbody>
                <form action="{{route('crossjoin.data')}}" method="POST">
                    @csrf 
                    @foreach ($excelHeader as $key=>$item)
                        @if ($item != 'id')
                        <tr>
                            <td>
                                <select id="{{'excel-select-'.$key}}" name="excel[]" class="select bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option selected>Select Column</option>
                                @foreach ($excelHeader as $item)
                                    <option value="{{$item}}">{{$item}}</option>  
                                @endforeach
                                </select>
                            </td>
                            <td>
                                <select id="{{'table-select-'.$key}}"  name="table[]" class="select bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected>Select Column</option>
                                 @foreach ($tableHeader as $item)
                                    <option value="{{$item}}">{{$item}}</option>  
                                @endforeach
                                </select>
                            </td>
                        </tr> 
                        @endif  
                    @endforeach
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Connect</button>

                </form>
            </tbody>
        </table>
    </div>
</body>
</html>