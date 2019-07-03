import {
  Injectable
} from '@angular/core';

@Injectable()
export class TimeDateService {

  date: any;
  time: any;
  presentDay: any;
  greet: string;
  hours: any;
  today: any;
  days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];

  constructor() {}

  getDay() {
    /* get present day as number */
    this.today = this.date.getDay();
    /* present day to show date in header */
    return this.presentDay = this.days[this.today];
  }
  getDate() {
    /* date */
    return this.date = new Date();
  }
  getTime() {
    /* time */
    return this.time = new Date();
  }
  getGreeting() {
    /* hour */
    this.hours = this.date.getHours();
    /* greeting based on time */
    if (this.hours < 12) {
      this.greet = 'Good Morning';
    } else if (this.hours >= 12 && this.hours <= 17) {
      this.greet = 'Good Afternoon';
    } else if (this.hours >= 17 && this.hours <= 24) {
      this.greet = 'Good Evening';
    }
    return this.greet;
  }

}
