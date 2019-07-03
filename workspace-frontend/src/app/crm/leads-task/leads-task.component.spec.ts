import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LeadsTaskComponent } from './leads-task.component';

describe('LeadsTaskComponent', () => {
  let component: LeadsTaskComponent;
  let fixture: ComponentFixture<LeadsTaskComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LeadsTaskComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LeadsTaskComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
