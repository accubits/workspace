import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WorkTimeDetailsComponent } from './work-time-details.component';

describe('WorkTimeDetailsComponent', () => {
  let component: WorkTimeDetailsComponent;
  let fixture: ComponentFixture<WorkTimeDetailsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WorkTimeDetailsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WorkTimeDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
