import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ApprovedTableComponent } from './approved-table.component';

describe('ApprovedTableComponent', () => {
  let component: ApprovedTableComponent;
  let fixture: ComponentFixture<ApprovedTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ApprovedTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ApprovedTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
