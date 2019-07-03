import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CrmContainerComponent } from './crm-container.component';

describe('CrmContainerComponent', () => {
  let component: CrmContainerComponent;
  let fixture: ComponentFixture<CrmContainerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CrmContainerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CrmContainerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
