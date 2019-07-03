import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LeadsDetailComponent } from './leads-detail.component';

describe('LeadsDetailComponent', () => {
  let component: LeadsDetailComponent;
  let fixture: ComponentFixture<LeadsDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LeadsDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LeadsDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
