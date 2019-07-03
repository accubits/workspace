import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WorkHoursStartComponent } from './work-hours-start.component';

describe('WorkHoursStartComponent', () => {
  let component: WorkHoursStartComponent;
  let fixture: ComponentFixture<WorkHoursStartComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WorkHoursStartComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WorkHoursStartComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
