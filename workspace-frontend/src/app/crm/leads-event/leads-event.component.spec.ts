import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LeadsEventComponent } from './leads-event.component';

describe('LeadsEventComponent', () => {
  let component: LeadsEventComponent;
  let fixture: ComponentFixture<LeadsEventComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LeadsEventComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LeadsEventComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
