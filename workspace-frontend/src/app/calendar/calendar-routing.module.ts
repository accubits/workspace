import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CalendarComponent} from './calendar.component';
import { CalendarMainComponent } from './calendar-main/calendar-main.component';
import { CalendarDayComponent } from './calendar-day/calendar-day.component';
import { CalendarWeekComponent } from './calendar-week/calendar-week.component';
import { CalendarNvbarComponent } from './calendar-nvbar/calendar-nvbar.component'


const routes: Routes = [
  {
    path:'',
    component: CalendarComponent,
    children:[
      { path:'',redirectTo: 'calendarMonth', pathMatch: 'full' },
      { path :'calendarMonth', component:CalendarMainComponent },
      { path :'calendarWeek', component:CalendarWeekComponent },
      { path :'calendarDay', component:CalendarDayComponent },
    ]
   
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CalendarRoutingModule { }
