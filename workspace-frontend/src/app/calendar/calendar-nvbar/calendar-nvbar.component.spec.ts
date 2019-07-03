import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CalendarNvbarComponent } from './calendar-nvbar.component';

describe('CalendarNvbarComponent', () => {
  let component: CalendarNvbarComponent;
  let fixture: ComponentFixture<CalendarNvbarComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CalendarNvbarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CalendarNvbarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
