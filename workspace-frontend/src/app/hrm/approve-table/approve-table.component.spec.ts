import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ApproveTableComponent } from './approve-table.component';

describe('ApproveTableComponent', () => {
  let component: ApproveTableComponent;
  let fixture: ComponentFixture<ApproveTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ApproveTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ApproveTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
