'use client'
import React,{useEffect, useState} from 'react'

export default function page() {
    const excelHeader = localStorage.getItem('excelHeader') !=  undefined ? JSON.parse(localStorage.getItem('excelHeader')) :   [];
    const tableHeader = localStorage.getItem('tableHeader') != undefined ? JSON.parse(localStorage.getItem('tableHeader')) :  [];
    const [uploadStatus, setUploadStatus] = useState('');
    const [selectedExcelOptions, setSelectedExcelOptions] = useState([]);
    const [selectedTableOptions, setSelectedTableOptions] = useState([]);
    const matchData = JSON.parse(localStorage.getItem('matchData'))

    const handleSelectExcelChange = (event,match) => {
      const { value } = event.target;
      let data = Array.from(new Set([...selectedExcelOptions, value]));
      setSelectedExcelOptions(data);
      
    };
    const  handleSelectTableChange =  (event) => {
      const { value } = event.target;
      let data = Array.from(new Set([...selectedTableOptions, value]));
      setSelectedTableOptions(data);
    };
    const  handleSubmit = async (event) => {
        event.preventDefault();
       try{
        const response = await fetch('http://localhost:8000/api/cross/join', {
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              "Access-Control-Allow-Origin": "*"
            },
            method: 'POST',
            body: JSON.stringify({
                'excel' : selectedExcelOptions,
                'table' : selectedTableOptions
            }),
          });

          let data = await response.json();
          if(data.status){
            alert('Data inserted Successfully')
            setUploadStatus('Data inserted Successfully');
          }
          
       }catch(e){
        console.error(e);
       }

    
      };

      useEffect(() => {
         setSelectedExcelOptions(matchData)

         setSelectedTableOptions(matchData)
      },[])
  return (
    <div className='container mx-auto'>
        <form onSubmit={handleSubmit}>
            

<div className="relative overflow-x-auto">
    <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" className="px-6 py-3">
                    Excel Column 
                </th>
                <th scope="col" className="px-6 py-3">
                    Table Column 
                </th>
              
            </tr>
        </thead>
        <tbody>
            {
                 excelHeader.map((item,mainKey) => (
                    <tr className="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
              
                        <td>
                                    <select name="excel[]"  onChange={handleSelectExcelChange} id="" className='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'>
                                   
                                      
                                       {
                                      
                                        matchData.length > 0 ? 
                                                matchData.map((match,key) => {

                                                    // mainKey == key  ? handleSelectExcelChange(undefined,match) : ''
                                                        
                                                 return    mainKey == key ?    <option  selected value={match}>{match}</option>  : ''
                                                    
                                                    
                                        }) :
                                        <>
                                        <option selected disabled={true}>Select Column</option>
                                            {
                                                 excelHeader.map((val,i) => (
                                                    <option   value={val}>{val}</option> 
                                                  ))
                                            }
                                       </>

                                      }

                                       {
                                        mainKey > matchData.length - 1  ? 
                                        <>
                                        <option selected disabled={true}>Select Column</option>
                                            {
                                                 excelHeader.map((val,i) => (
                                                    <option   value={val}>{val}</option> 
                                                  ))
                                            }
                                       </>
                                       : 
                                        ''
                                       }
                                        
                                           
                                    
                                   </select>
                        </td>
                        <td>
                                    <select name="table[]" id=""  onChange={handleSelectTableChange} className='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'>
                                    
                                       
                                    {
                                      
                                      matchData.length > 0 ? 
                                      matchData.map((match,key) =>(
                                          mainKey == key ? <option  selected value={match}>{match}</option> : ''
                                      )) :
                                      <>
                                      <option selected disabled={true}>Select Column</option>
                                          {
                                               tableHeader.map((val,i) => (
                                                  <option   value={val}>{val}</option> 
                                                ))
                                          }
                                     </>

                                    }

                                     {
                                      mainKey > matchData.length - 1  ? 
                                      <>
                                      <option selected disabled={true}>Select Column</option>
                                          {
                                               tableHeader.map((val,i) => (
                                                  <option   value={val}>{val}</option> 
                                                ))
                                          }
                                     </>
                                     : 
                                      ''
                                     }
                                   </select>
                        </td>

                    </tr>
                 ))
            }
           
            
            
        </tbody>
    </table>
    <button type="submit">Submit</button>
</div>

        </form>
    </div>
  )
}
